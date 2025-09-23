<?php

declare(strict_types=1);

use Yuxin\Weather\Exceptions\HttpException;
use Yuxin\Weather\Exceptions\InvalidArgumentException;

describe('Exceptions', function (): void {
    describe('InvalidArgumentException', function (): void {
        test('can be thrown with message', function (): void {
            expect(function (): void {
                throw new InvalidArgumentException('Test message');
            })->toThrow(InvalidArgumentException::class, 'Test message');
        });

        test('can be thrown with message and code', function (): void {
            try {
                throw new InvalidArgumentException('Test message', 400);
            } catch (InvalidArgumentException $e) {
                expect($e->getMessage())->toBe('Test message');
                expect($e->getCode())->toBe(400);
            }
        });
    });

    describe('HttpException', function (): void {
        test('can be thrown with message', function (): void {
            expect(function (): void {
                throw new HttpException('HTTP error');
            })->toThrow(HttpException::class, 'HTTP error');
        });

        test('can be thrown with previous exception', function (): void {
            $previous = new Exception('Previous error');

            try {
                throw new HttpException('HTTP error', 500, $previous);
            } catch (HttpException $e) {
                expect($e->getMessage())->toBe('HTTP error');
                expect($e->getCode())->toBe(500);
                expect($e->getPrevious())->toBe($previous);
            }
        });
    });
});
