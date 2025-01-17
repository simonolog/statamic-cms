<?php

namespace Tests\Modifiers;

use Statamic\Fields\Value;
use Statamic\Fieldtypes\Bard;
use Statamic\Modifiers\Modify;
use Tests\TestCase;

class BardTextTest extends TestCase
{
    /** @test */
    public function it_extracts_bard_text()
    {
        $data = [
            [
                'type' => 'paragraph',
                'content' => [
                    ['type' => 'text', 'text' => 'This is a paragraph with '],
                    ['type' => 'text', 'marks' => [['type' => 'bold']], 'text' => 'bold'],
                    ['type' => 'text', 'text' => ' and '],
                    ['type' => 'text', 'marks' => [['type' => 'italic']], 'text' => 'italic'],
                    ['type' => 'text', 'text' => ' text.'],
                ],
            ],
            [
                'type' => 'paragraph',
            ],
            [
                'type' => 'set',
                'attrs' => [
                    'values' => [
                        'type' => 'image',
                        'image' => 'test.jpg',
                        'caption' => 'test',
                    ],
                ],
            ],
            [
                'type' => 'paragraph',
                'content' => [
                    ['type' => 'text', 'text' => 'Another paragraph.'],
                ],
            ],
        ];

        $expected = 'This is a paragraph with bold and italic text. Another paragraph.';

        $this->assertEquals($expected, $this->modify($data));
    }

    /** @test */
    public function it_extracts_bard_text_from_single_node()
    {
        $data = [
            'type' => 'paragraph',
            'content' => [
                ['type' => 'text', 'text' => 'This is a paragraph.'],
            ],
        ];

        $expected = 'This is a paragraph.';

        $this->assertEquals($expected, $this->modify($data));
    }

    /** @test */
    public function it_extracts_bard_text_from_value_object()
    {
        $data = new Value([
            [
                'type' => 'paragraph',
                'content' => [
                    ['type' => 'text', 'text' => 'This is a paragraph.'],
                ],
            ],
        ], 'content', new Bard());

        $expected = 'This is a paragraph.';

        $this->assertEquals($expected, $this->modify($data));
    }

    /** @test */
    public function it_handles_null()
    {
        $this->assertEquals('', $this->modify(null));
    }

    public function modify($arr, ...$args)
    {
        return Modify::value($arr)->bard_text($args)->fetch();
    }
}
