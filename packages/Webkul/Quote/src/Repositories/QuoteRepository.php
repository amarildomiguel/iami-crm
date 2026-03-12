<?php

namespace Webkul\Quote\Repositories;

use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Webkul\Attribute\Repositories\AttributeRepository;
use Webkul\Attribute\Repositories\AttributeValueRepository;
use Webkul\Core\Eloquent\Repository;
use Webkul\Quote\Contracts\Quote;

class QuoteRepository extends Repository
{
    /**
     * Searchable fields.
     */
    protected $fieldSearchable = [
        'subject',
        'description',
        'person_id',
        'person.name',
        'user_id',
        'user.name',
    ];

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(
        protected AttributeRepository $attributeRepository,
        protected AttributeValueRepository $attributeValueRepository,
        protected QuoteItemRepository $quoteItemRepository,
        Container $container
    ) {
        parent::__construct($container);
    }

    /**
     * Specify model class name.
     *
     * @return mixed
     */
    public function model()
    {
        return Quote::class;
    }

    /**
     * Calculate IVA for quote data.
     *
     * Aplica a taxa de IVA de 14% conforme o Código do IVA Angolano (Lei n.º 7/19).
     * Se o cliente ou a proposta tiver isenção de IVA, o valor calculado será 0.
     */
    protected function calculateIva(array $data): array
    {
        $ivaExempt = ! empty($data['iva_exempt']) && $data['iva_exempt'];
        $ivaPercentage = isset($data['iva_percentage']) ? (float) $data['iva_percentage'] : 14.00;
        $subTotal = isset($data['sub_total']) ? (float) $data['sub_total'] : 0.0;
        $discountAmount = isset($data['discount_amount']) ? (float) $data['discount_amount'] : 0.0;

        $subTotalBeforeIva = $subTotal - $discountAmount;

        if ($ivaExempt || $ivaPercentage <= 0) {
            $ivaAmount = 0.0;
        } else {
            $ivaAmount = round($subTotalBeforeIva * ($ivaPercentage / 100), 2);
        }

        $data['iva_amount'] = $ivaAmount;
        $data['sub_total_before_iva'] = $subTotalBeforeIva;
        $data['iva_percentage'] = $ivaPercentage;

        // Recalcular grand_total incluindo IVA (subtotal - desconto + ajuste + IVA)
        $adjustmentAmount = isset($data['adjustment_amount']) ? (float) $data['adjustment_amount'] : 0.0;
        $data['grand_total'] = round($subTotalBeforeIva + $ivaAmount + $adjustmentAmount, 2);

        return $data;
    }

    /**
     * Create.
     *
     * @return Quote
     */
    public function create(array $data)
    {
        $data = $this->calculateIva($data);

        $quote = parent::create($data);

        $this->attributeValueRepository->save(array_merge($data, [
            'entity_id' => $quote->id,
        ]));

        foreach ($data['items'] as $itemData) {
            $this->quoteItemRepository->create(array_merge($itemData, [
                'quote_id' => $quote->id,
            ]));
        }

        return $quote;
    }

    /**
     * Update.
     *
     * @param  int  $id
     * @param  array  $attribute
     * @return Quote
     */
    public function update(array $data, $id, $attributes = [])
    {
        $data = $this->calculateIva($data);

        $quote = $this->find($id);

        parent::update($data, $id);

        /**
         * If attributes are provided then only save the provided attributes and return.
         */
        if (! empty($attributes)) {
            $conditions = ['entity_type' => $data['entity_type']];

            if (isset($data['quick_add'])) {
                $conditions['quick_add'] = 1;
            }

            $attributes = $this->attributeRepository->where($conditions)
                ->whereIn('code', $attributes)
                ->get();

            $this->attributeValueRepository->save(array_merge($data, [
                'entity_id' => $quote->id,
            ]), $attributes);

            return $quote;
        }

        $this->attributeValueRepository->save(array_merge($data, [
            'entity_id' => $quote->id,
        ]));

        $previousItemIds = $quote->items->pluck('id');

        if (isset($data['items'])) {
            foreach ($data['items'] as $itemId => $itemData) {
                if (Str::contains($itemId, 'item_')) {
                    $this->quoteItemRepository->create(array_merge($itemData, [
                        'quote_id' => $id,
                    ]));
                } else {
                    if (is_numeric($index = $previousItemIds->search($itemId))) {
                        $previousItemIds->forget($index);
                    }

                    $this->quoteItemRepository->update($itemData, $itemId);
                }
            }
        }

        foreach ($previousItemIds as $itemId) {
            $this->quoteItemRepository->delete($itemId);
        }

        return $quote;
    }

    /**
     * Retrieves customers count based on date.
     *
     * @return number
     */
    public function getQuotesCount($startDate, $endDate)
    {
        return $this
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get()
            ->count();
    }
}
