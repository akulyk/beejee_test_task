<?php

namespace app\template;

interface TemplateRenderer
{
    public function render($name, array $params = []): string;
}
