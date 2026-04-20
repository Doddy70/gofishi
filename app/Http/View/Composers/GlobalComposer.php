<?php

namespace App\Http\View\Composers;

use App\Http\Controllers\FrontEnd\MiscellaneousController;
use Illuminate\View\View;

class GlobalComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view)
    {
        $misc = new MiscellaneousController();
        $language = get_lang();
        
        $data = [
            'currentLanguageInfo' => $language,
            'basicInfo' => bs(),
            'websiteInfo' => bs(), // template uses both
            'breadcrumb' => $misc->getBreadcrumb(),
            'pageHeading' => $language ? $misc->getPageHeading($language) : null,
        ];

        $view->with($data);
    }
}
