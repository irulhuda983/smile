<?php
namespace Syslog\Types;

const TotalFields = 21;

class FieldType
{
    const Timestamp = 'timestamp';
    const LogLevel = 'level';
    const TransactionID = 'transaction_id';
    const ServiceName = 'service_name';
    const Endpoint = 'endpoint';
    const Protocol = 'protocol';
    const HttpMethodType = 'http_method_type';
    const ExecutionMethod = 'exec_method';
    const ContentType = 'content_type';
    const FunctionName = 'function_name';
    const CallerInfo = 'caller_info';
    const ExecutionTime = 'exec_time';
    const ServerIP = 'server_ip';
    const ClientIP = 'client_ip';
    const EventName = 'event_name';
    const TraceID = 'trace_id';
    const PrevTransactionID = 'prev_transaction_id';
    const Body = 'body';
    const Result = 'result';
    const Error = 'error';
    const LogPhase = 'phase';
    const Message = 'message';
}


class FieldOrder
{
    const Timestamp = 0;
    const LogLevel = 1;
    const TransactionID = 2;
    const ServiceName = 3;
    const Endpoint = 4;
    const Protocol = 5;
    const HttpMethodType = 6;
    const ExecutionMethod = 7;
    const ContentType = 8;
    const FunctionName = 9;
    const CallerInfo = 10;
    const ExecutionTime = 11;
    const ServerIP = 12;
    const ClientIP = 13;
    const EventName = 14;
    const TraceID = 15;
    const PrevTransactionID = 16;
    const Body = 17;
    const Result = 18;
    const Error = 19;
    const LogPhase = 20;
    const Message = 21;
}
