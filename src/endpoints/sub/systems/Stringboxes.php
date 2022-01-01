<?php

namespace meteocontrol\client\vcomapi\endpoints\sub\systems;

use meteocontrol\vcomapi\model\Stringbox;
use meteocontrol\client\vcomapi\endpoints\EndpointInterface;
use meteocontrol\client\vcomapi\endpoints\sub\SubEndpoint;

class Stringboxes extends SubEndpoint {

    /**
     * @param EndpointInterface $parent
     */
    public function __construct(EndpointInterface $parent) {
        $this->uri = '/stringboxes';
        $this->api = $parent->getApiClient();
        $this->parent = $parent;
    }

    /**
     * Stringbox[]
     */
    public function get() {
        $invertersJson = $this->api->run($this->getUri());
        return Stringbox::deserializeArray($this->jsonDecode($invertersJson, true)['data']);
    }

    /**
     * @return Bulk
     */
    public function bulk() {
        return new Bulk($this);
    }
}
