<?php

namespace Modules\Chat\Enums;

enum DeliveryManStatusEnum: string
{
    case ACTIVE = "active";
    case INACTIVE = "inactive";
    case BANED = "baned";
    case EX_EMPLOYEE = "ex-employee";
    case ON_DUTY = "On Duty";
    case OFF_DUTY = "Off Duty";
    case SUSPENDED = "Suspended";
    case TERMINATED = "Terminated";
    case RESIGNED = "Resigned";

    public static function get_status(string $status): DeliveryManStatusEnum|string
    {
        return match ($status){
            "active" => DeliveryManStatusEnum::ACTIVE,
            "inactive" => DeliveryManStatusEnum::INACTIVE,
            "baned" => DeliveryManStatusEnum::BANED,
            "ex-employee" => DeliveryManStatusEnum::EX_EMPLOYEE,
            "On Duty" => DeliveryManStatusEnum::ON_DUTY,
            "Off Duty" => DeliveryManStatusEnum::OFF_DUTY,
            "Suspended" => DeliveryManStatusEnum::SUSPENDED,
            "Terminated" => DeliveryManStatusEnum::TERMINATED,
            "Resigned" => DeliveryManStatusEnum::RESIGNED,
            default => ''
        };
    }

    public function getStatuses(): array
    {
        return array_column((array) DeliveryManStatusEnum::class, "value");
    }
}