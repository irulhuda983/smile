<?php
    namespace Syslog;

    interface LoggerInterface {
        public function info(string $message, ...$fields): void;
        public function debug(string $message, ...$fields): void;
        public function warn(string $message, ...$fields): void;
        public function error(string $message, ...$fields): void;
        public function fatal(string $message, ...$fields): void;
        public function with(...$fields): LoggerInterface;
        public function use(...$fields): LoggerInterface;
        public function useHttpReq(): LoggerInterface;
        public function start(): LoggerInterface;
        public function elapsed(): LoggerInterface;
        public function stop(): LoggerInterface;
        public function get(int $index);
    }
?>