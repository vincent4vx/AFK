<?php

/*
 * The MIT License
 *
 * Copyright 2014 Vincent Quatrevieux <quatrevieux.vincent@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace app\flux\parser;

/**
 * Description of Join
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Join implements Parser {
    /**
     *
     * @var \app\model\Account
     */
    private $account;
    
    /**
     *
     * @var \app\model\Event
     */
    private $event;
    
    /**
     *
     * @var \system\helper\Url
     */
    private $url;
    
    function __construct(\app\model\Account $account, \app\model\Event $event, \system\helper\Url $url) {
        $this->account = $account;
        $this->event = $event;
        $this->url = $url;
    }
    
    public function parseRow(array $row) {
        $data =  explode(';', $row['FLUX_DATA']);
        
        $eventName = $this->event->getEventName($data[1]);
        $pseudo = $this->account->getPseudoByUserId($data[0]);
        
        return array(
            'senderUrl' => $this->url->secureUrl('account', 'profile', $data[0]),
            'sender' => $pseudo,
            'targetUrl' => $this->url->secureUrl('events', 'show', $data[1]),
            'target' => $eventName,
            'date' => $row['FLUX_DATE'],
            'message' => <<<EOD
            <strong>$pseudo</strong> a rejoin l'évènement <strong>$eventName</strong> !
EOD
        );
    }
}
