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
 * Description of Eventstart
 *
 * @author Vincent Quatrevieux <quatrevieux.vincent@gmail.com>
 */
class Eventstart implements Parser {
    /**
     *
     * @var \system\helper\Url
     */
    private $url;
    
    function __construct(\system\helper\Url $url) {
        $this->url = $url;
    }

    public function parseRow(array $row) {
        return array(
            'senderUrl' => $this->url->secureUrl('events', 'show', $row['TARGET_ID']),
            'sender' => $row['TARGET_NAME'],
            'date' => $row['FLUX_DATE'],
            'message' => "L'évènement <strong>{$row['TARGET_NAME']}</strong> a commencé."
        );
    }
}
