<x-validation.error />
<table class="table_activation">
    <thead>
        <tr>
            <th>{{ __('ID') }}</th>
            <th>{{ __('Operation') }}</th>
            <th>{{ __('Category') }}</th>
            <th>{{ __('Cost') }}</th>
            <th>{{ __('Action') }}</th>
        </tr>
    </thead>
    <tbody>
        @if ($operations_costs->total() >= 1)
            @foreach ($operations_costs as $operation)
                <tr>
                    <td>{{ $operation->id }}</td>
                    <td>
                        @if ($operation->operation_type == 1)
                            {{ __('Make Request') }}
                        @elseif ($operation->operation_type == 2)
                            {{ __('Make Offer') }}
                        @elseif ($operation->operation_type == 3)
                            {{ __('Update Request') }}
                        @elseif ($operation->operation_type == 4)
                            {{ __('Update Offer') }}
                        @elseif ($operation->operation_type == 5)
                            {{ __('Delete Request') }}
                        @elseif ($operation->operation_type == 6)
                            {{ __('Delete Offer') }}
                        @elseif ($operation->operation_type == 7)
                            {{ __('Make advertisement') }}
                        @else
                            {{ '' }}
                        @endif
                    </td>
                    <td>
                        @if ($operation->category_slug == \App\Enums\MachineType::heavyEquipment->value)
                            {{ __('Heavy Equipments') }}
                        @elseif ($operation->category_slug == \App\Enums\MachineType::vehicleRental->value)
                            {{ __('Vehicle Rental') }}
                        @elseif ($operation->category_slug == \App\Enums\MachineType::craneRental->value)
                            {{ __('Crane Rental') }}
                        @elseif ($operation->category_slug == \App\Enums\MachineType::accommodationServices->value)
                            {{ __('Accommodation Services') }}
                        @elseif ($operation->category_slug == \App\Enums\MachineType::generatorRental->value)
                            {{ __('Generator Rental') }}
                        @elseif ($operation->category_slug == \App\Enums\MachineType::scaffoldingTools->value)
                            {{ __('Scaffolding Tools') }}
                        @elseif ($operation->category_slug == \App\Enums\MachineType::cableTools->value)
                            {{ __('Cable Tools') }}
                        @else
                            {{ '' }}
                        @endif
                    </td>
                    <td>{{ $operation->cost }}</td>
                    <td>
                        <x-status.table.select-action :title="__('Select Action')" />
                        <ul class="dropdown-menu status_dropdown__list">
                            <li class="status_dropdown__item">
                                <a class="btn dropdown-item status_dropdown__list__link operation_details"
                                    data-bs-toggle="modal" data-bs-target="#operatinoDetailsModal"
                                    data-operation_id="{{ $operation->id }}"
                                    data-operation_type="
                                                            @if ($operation->operation_type == 1) {{ __('Make Request') }}
                                                            @elseif ($operation->operation_type == 2)
                                                                {{ __('Make Offer') }}
                                                            @elseif ($operation->operation_type == 3)
                                                                {{ __('Update Request') }}
                                                            @elseif ($operation->operation_type == 4)
                                                                {{ __('Update Offer') }}
                                                            @elseif ($operation->operation_type == 5)
                                                                {{ __('Delete Request') }}
                                                            @elseif ($operation->operation_type == 6)
                                                                {{ __('Delete Offer') }}
                                                            @elseif ($operation->operation_type == 7)
                                                                {{ __('Make advertisement') }}
                                                            @else
                                                                {{ '' }} @endif
                                                        "
                                    data-category_slug="
                                                        @if ($operation->category_slug == \App\Enums\MachineType::heavyEquipment->value) {{ __('Heavy Equipments') }}
                                                        @elseif ($operation->category_slug == \App\Enums\MachineType::vehicleRental->value)
                                                            {{ __('Vehicle Rental') }}
                                                        @elseif ($operation->category_slug == \App\Enums\MachineType::craneRental->value)
                                                            {{ __('Crane Rental') }}
                                                        @elseif ($operation->category_slug == \App\Enums\MachineType::accommodationServices->value)
                                                            {{ __('Accommodation Services') }}
                                                        @elseif ($operation->category_slug == \App\Enums\MachineType::generatorRental->value)
                                                            {{ __('Generator Rental') }}
                                                        @elseif ($operation->category_slug == \App\Enums\MachineType::scaffoldingTools->value)
                                                            {{ __('Scaffolding Tools') }}
                                                        @elseif ($operation->category_slug == \App\Enums\MachineType::cableTools->value)
                                                            {{ __('Cable Tools') }}
                                                        @else
                                                            {{ '' }} @endif
                                                    "
                                    data-cost="{{ $operation->cost }}">
                                    {{ __('View Operation Details') }}
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
            @endforeach
        @else
            <x-table.no-data-found :colspan="'7'" :class="'text-danger text-center py-5'" />
        @endif
    </tbody>
</table>
<x-pagination.laravel-paginate :allData="$operations_costs" />
