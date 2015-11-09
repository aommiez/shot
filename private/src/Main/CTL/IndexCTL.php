<?php
/**
 * Created by PhpStorm.
 * User: p2
 * Date: 7/15/14
 * Time: 11:27 AM
 */

namespace Main\CTL;


use Main\Context\Context;
use Main\Http\RequestInfo;
use Main\View\HtmlView;
use Main\View\JsonView;
use Main\ThirdParty\Xcrud\Xcrud;

/**
 * @Restful
 * @uri /
 */
class IndexCTL extends BaseCTL {

    /**
     * @GET
     */
    public function getViewIndex () {
        return new JsonView(["view"=> 'index']);
    }

    /**
     * @GET
     * @uri setting
     */
    public function getViewSetting(){
        // if PHP under version 5.4 use return new JsonView(array("id"=> $id));
        return new JsonView(["view"=> 'setting']);
    }
}
