<?php

namespace DT\Home\Apps;

class DiscipleTools extends App
{
    public function config(): array
    {
        return [
            "name" => "Disciple.Tools",
            "type" => "Web View",
            'creation_type' => 'code',
            "icon" => "/wp-content/themes/disciple-tools-theme/dt-assets/images/dt-caret.png",
            'url' => site_url( '/' ),
            "sort" => 0,
            "slug" => "disciple-tools",
            "is_hidden" => false,
            'open_in_new_tab' => false
        ];
    }


    public function authorized(): bool
    {
        // Implement the authorization logic here
        return true;
    }
}
