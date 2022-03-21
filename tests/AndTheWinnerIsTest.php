<?php

namespace MaitreHibou\TicTacToe\Tests;

use PHPUnit\Framework\TestCase;
use RuntimeException;

use function MaitreHibou\TicTacToe\andTheWinnerIs;

class AndTheWinnerIsTest extends TestCase
{
    public function boardsProvider()
    {
        return [
            [
                [
                    ['O', 'O', 'X'],
                    ['X', 'X', 'X'],
                    ['O', 'X', 'O'],
                ],
                'X',
            ],
            [
                [
                    ['O', 'X', 'O'],
                    ['X', 'X', 'O'],
                    ['O', 'X', 'X']
                ],
                'X',
            ],
            [
                [
                    ['O', 'O', 'X'],
                    ['X', 'O', 'X'],
                    ['O', 'X', 'O'],
                ],
                'O',
            ],
            [
                [
                    ['O', 'X', 'X'],
                    ['O', 'X', 'O'],
                    ['X', 'O', 'X'],
                ],
                'X',
            ],
            [
                [
                    ['O', 'O', 'X'],
                    ['X', 'X', 'O'],
                    ['O', 'X', 'O']
                ],
                'Tie',
            ],
        ];
    }

    /**
     * @dataProvider boardsProvider
     */
    public function testBoardResolution($board, $expected)
    {
        $this->assertEquals($expected, andTheWinnerIs($board));
    }

    public function testFunctionAcceptsBoardAsStrings()
    {
        $board = <<<BOARD
        O O X
        X X X
        O X O
        BOARD;
        $this->assertEquals('X', andTheWinnerIs($board));

        $board = file_get_contents(sprintf('%s/dummy/board.txt', dirname(__FILE__)));
        $this->assertEquals('O', andTheWinnerIs($board));
    }

    public function testFunctionAcceptsVariousSizedBoards()
    {
        $this->assertEquals('X', andTheWinnerIs([
            ['O', 'X', 'O', 'X'],
            ['X', 'X', 'X', 'X'],
            ['O', 'X', 'X', 'O'],
            ['X', 'O', 'O', 'O'],
        ]));

        $this->assertEquals('X', andTheWinnerIs([
            ['X', 'X', 'X', 'X', 'X'],
            ['X', 'O', 'O', 'X', 'O'],
            ['X', 'X', 'O', 'X', 'O'],
            ['X', 'O', 'X', 'O', 'O'],
            ['O', 'O', 'X', 'O', 'O'],
        ]));
    }

    public function testExceptionIsThrownWhenBoardIsTooSmall()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Provided board is invalid. Boards must be squares of at least a size of 3x3.');

        andTheWinnerIs([
            ['0', 'X'],
            ['X', 'O'],
        ]);
    }

    public function testInProgressBoards()
    {
        $this->assertEquals('In progress', andTheWinnerIs([
            ['O', 'O', ''],
            ['X', '', 'O'],
            ['O', 'X', 'O'],
        ]));

        $board = <<<BOARD
        O O
        X   X
        O X O
        BOARD;
        $this->assertEquals('In progress', andTheWinnerIs($board));
    }

    public function testExceptionIsThrownWhenInvalidCharacterIsProvided()
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('An invalid character has been provided. Use only Xs, Os and spaces.');

        andTheWinnerIs([
            ['Y', 'O', ''],
            ['X', '', 'O'],
            ['O', 'X', 'O']
        ]);
    }
}
