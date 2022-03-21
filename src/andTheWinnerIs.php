<?php

namespace MaitreHibou\TicTacToe;

use RuntimeException;

function andTheWinnerIs(array|string $board): string
{
    /**
     * If the provided board is a string, we need to cast it as an array. We will assume that the needed format for the
     * string should be as following :
     * O X O
     * X O X
     * O X O
     * Each cell is separated by a space character, and an empty cell is represented by a space character
     */
    if (is_string($board)) {
        $boardToFormat = explode("\n", trim($board));

        $board = [];
        foreach ($boardToFormat as $boardLine) {

            if (preg_match('/\s{2}/', $boardLine)) {
                return 'In progress';
            }

            $boardLine = str_replace(' ', '_', $boardLine);
            array_push($board, explode('_', $boardLine));
        }
    }

    $boardSize = count($board);

    /**
     * Once the board is built, we can check if it is a valid one.
     */
    if ($boardSize < 3) {
        throw new RuntimeException('Provided board is invalid. Boards must be squares of at least a size of 3x3.');
    }

    /**
     * 1 : Testing horizontal lines
     *
     * Testing horizontal lines is pretty straightforward. We loop on each board line, and count if there are values
     * which are repeated $boardSize times.
     */
    foreach ($board as $line) {
        $valuesCount = array_count_values($line);

        foreach (array_keys($valuesCount) as $value) {
            if (!in_array($value, ['X', 'O', ''])) {
                throw new RuntimeException('An invalid character has been provided. Use only Xs, Os and spaces.');
            }
        }

        if (array_key_exists('', $valuesCount) && 0 < $valuesCount['']) {
            return 'In progress';
        }

        foreach ($valuesCount as $value => $count) {
            if ($boardSize === $count) {
                return $value;
            }
        }
    }

    /**
     * 2 : Testing vertical lines
     *
     * To test vertical lines, we are going to loop on values from the first line, and test if value at the same index
     * on other lines is the same. If so, we increment a counter, and if it is equal to $boardSize, its a win !
     */
    foreach ($board[0] as $cellIndex => $value) {
        $winStreak = 1;
        for ($lineIndex = 1; $lineIndex < $boardSize; ++$lineIndex) {
            if ($board[$lineIndex][$cellIndex] === $value) {
                ++$winStreak;
            }
        }

        if ($boardSize === $winStreak) {
            return $value;
        }
    }

    /**
     * 3 : Testing diagonals
     *
     * To test a diagonal, we are going to get the first cell value as the test ($board[0]|0]), and run a comparison
     * by looping on each line, and each cell which is the same index as the current line (our board is a square). If
     * we encounter the same value as the test, we increment a win streak counter, and if it is equal to $boardSize at
     * the end, it's a win !
     * The tricky part of this test, is that we need to check if a diagonal is filled with the same symbol on both
     * directions (NW => SE and NE => SW). To do this, we are going to run the same check on the board, and on a
     * reversed version.
     */
    foreach ([$board, array_reverse($board)] as $testedBoard) {
        $lineIndex = 1;
        $streak = 1;
        while ($lineIndex < $boardSize) {
            $testing = $testedBoard[0][0];

            if ($testedBoard[$lineIndex][$lineIndex] === $testing) {
                $streak++;
            }

            $lineIndex++;
        }

        if ($boardSize === $streak) {
            return $testing;
        }
    }

    return 'Tie';
}
