<?php

namespace Webkul\Lead\Providers;

use Webkul\Core\Providers\BaseModuleServiceProvider;
use Webkul\Lead\Models\Hearing;
use Webkul\Lead\Models\Lead;
use Webkul\Lead\Models\LegalDeadline;
use Webkul\Lead\Models\LegalDocument;
use Webkul\Lead\Models\Pipeline;
use Webkul\Lead\Models\Product;
use Webkul\Lead\Models\Source;
use Webkul\Lead\Models\Stage;
use Webkul\Lead\Models\TimeEntry;
use Webkul\Lead\Models\Type;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        Lead::class,
        Pipeline::class,
        Product::class,
        Source::class,
        Stage::class,
        Type::class,
        // Modelos jurídicos angolanos
        LegalDocument::class,
        Hearing::class,
        TimeEntry::class,
        LegalDeadline::class,
    ];
}
