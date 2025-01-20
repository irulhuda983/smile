<?php
    namespace RateLimiter;

    interface RateLimiterInterface {
        public function create(): string;
        public function request($numOfRequest): bool;
    }
?>