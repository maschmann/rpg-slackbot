<?php

declare(strict_types=1);

namespace App\Slack\Infrastructure;

class SlackFormView
{
    public function characterForm(string $userName): string
    {
        return '{
          "type":"modal",
          "callback_id":"rpg-character-creation",
          "title":{
            "type":"plain_text",
            "text":"Character creation"
          },
          "blocks":[
            {
              "type":"section",
              "block_id":"section-identifier",
              "text":{
                "type":"mrkdwn",
                "text":"Here you can create a character for yourself :-D"
              }
            },
            {
              "type":"context",
              "elements":[
                {
                  "type":"plain_text",
                  "text":"Username: ' . $userName . '",
                  "emoji":true
                }
              ]
            },
            {
              "type":"actions",
              "elements":[
                {
                  "type":"static_select",
                  "placeholder":{
                    "type":"plain_text",
                    "text":"Select a character class (Design Type)!",
                    "emoji":true
                  },
                  "options":[
                    {
                      "text":{
                        "type":"plain_text",
                        "text":"*this is plain_text text*",
                        "emoji":true
                      },
                      "value":"value-0"
                    },
                    {
                      "text":{
                        "type":"plain_text",
                        "text":"*this is plain_text text*",
                        "emoji":true
                      },
                      "value":"value-1"
                    },
                    {
                      "text":{
                        "type":"plain_text",
                        "text":"*this is plain_text text*",
                        "emoji":true
                      },
                      "value":"value-2"
                    }
                  ],
                  "action_id":"character-class"
                }
              ]
            },
            {
              "type":"actions",
              "block_id":"save-button",
              "elements":[
                {
                  "type":"button",
                  "text":{
                    "type":"plain_text",
                    "text":"Create!"
                  },
                  "style":"primary",
                  "value":"save"
                }
              ]
            }
          ]
        }';
    }
}
