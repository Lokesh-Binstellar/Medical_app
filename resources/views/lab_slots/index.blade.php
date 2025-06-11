<!-- resources/views/calendar/index.blade.php -->
@extends('layouts.app')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-calendar.css') }}" />

    <style>
        .fc .fc-button-primary:not(.fc-prev-button):not(.fc-next-button):active,
        .fc .fc-button-primary:not(.fc-prev-button):not(.fc-next-button).fc-button-active {
            background-color: #f2f5f7 !important;
            border-color: #033a62 !important;
            color: #033a62;
        }

        .fc .fc-button-primary:not(.fc-prev-button):not(.fc-next-button):hover {
            background-color: #f2f5f7 !important;
            border-color: #033a62 !important;
            color: #033a62;
        }

        .fc .fc-view-harness .fc-event {
            background-color: #f2f5f7;
            color: #033a62;
        }

        .fc .fc-timegrid .fc-timegrid-event {
            color: white;
            background-color: rgba(160, 231, 160, 0.707);
        }

        .fc-event.slot-disabled {
            background-color: #f8d7da !important;
            /* light red background */
            border-color: #f5c2c7 !important;
            /* red border */
            color: #842029 !important;
            /* dark red text */
            opacity: 0.8;
            cursor: not-allowed;

        }

        .fc-event.slot-enabled {
            background-color: #f2f5f7 !important;
            /* soft green background */
            border-color: #0f5132 !important;
            /* deep green border */
            color: #0f5132 !important;
            /* rich green text */
            font-weight: 600;
            border-radius: 6px;
            box-shadow: 0 2px 6px rgba(0, 128, 0, 0.15);
            padding: 4px 6px;
            text-align: center;
        }

        .fc .fc-highlight {
            background-color: rgba(0, 123, 255, 0.15) !important;
            border: 1px dashed rgba(0, 123, 255, 0.4);
            border-radius: 4px;
        }
    </style>
@endsection
@section('content')
    <div class="card app-calendar-wrapper">
        <div class="row g-0">


            <!-- Calendar & Modal -->
            <div class="col app-calendar-content">
                <div class="card shadow-none border-0">
                    <div class="card-body pb-0">
                        <!-- FullCalendar -->
                        <div id="calendar"></div>
                    </div>
                </div>
                <div class="app-overlay"></div>

                <div class="offcanvas offcanvas-end event-sidebar" tabindex="-1" id="addEventSidebar"
                    aria-labelledby="addEventSidebarLabel">
                    <div class="offcanvas-header border-bottom">
                        <h5 class="offcanvas-title" id="addEventSidebarLabel">Select Slot</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body">
                        <form class="event-form pt-0" id="slotForm" method="POST">
                            @csrf
                            <input type="hidden" name="_method" value="POST" id="formMethod">
                            <input type="hidden" name="slot_id" id="slotId">
                        </form>

                        <hr>

                        <div id="slotList"></div>
                    </div>
                </div>


            </div>
            <!-- /Calendar & Modal -->
        </div>
    </div>

    <!-- / Content -->
    <div class="content-backdrop fade"></div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.js') }}"></script>

    {{-- @section('scripts')
    <!-- FullCalendar JS -->
    <script src="{{ asset('assets/vendor/libs/fullcalendar/fullcalendar.js') }}"></script> --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const slotListContainer = document.getElementById('slotList');
            const bsAddEventSidebar = new bootstrap.Offcanvas('#addEventSidebar');
            let calendar;

            async function fetchSlots() {
                const response = await fetch("{{ route('calendar.fetch') }}");
                return await response.json();
            }

            function initializeCalendar(events = []) {
                calendar = new Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    plugins: [dayGridPlugin, timegridPlugin, listPlugin, interactionPlugin],
                    events: events,
                    dayMaxEvents: 2, // 👉 Only 2 events visible, rest show "+X more"
                    eventDisplay: 'block', // ensures each event shows as block
                    height: 'auto',
                    selectable: true,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: ''

                    },
                    select: async function(info) {
                        const selectedDate = info.startStr.slice(0, 10);
                        const response = await fetch(
                            `{{ route('calendar.slotsByDate') }}?date=${selectedDate}`);
                        const existingSlots = await response.json();

                        let html = '';

                        for (let hour = 0; hour < 24; hour++) {
                            const from = `${selectedDate}T${String(hour).padStart(2, '0')}:00`;
                            const to = `${selectedDate}T${String((hour + 1) % 24).padStart(2, '0')}:00`;

                            const isActive = existingSlots.find(slot => slot.start === from && slot
                                .is_active);

                            html += `
            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                <div>${from.slice(11, 16)} - ${to.slice(11, 16)}</div>
                <div class="form-check form-switch">
                    <input class="form-check-input toggle-slot-switch" type="checkbox"
                        data-start="${from}" data-end="${to}" ${isActive ? 'checked' : ''}>
                </div>
            </div>`;
                        }

                        slotListContainer.innerHTML = html;

                        document.querySelectorAll('.toggle-slot-switch').forEach(switchBtn => {
                            switchBtn.addEventListener('change', async function() {
                                const start = this.getAttribute('data-start');
                                const end = this.getAttribute('data-end');

                                if (this.checked) {
                                    const response = await fetch(
                                        "{{ route('calendar.store') }}", {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document
                                                    .querySelector(
                                                        'meta[name=\"csrf-token\"]'
                                                    ).content
                                            },
                                            body: JSON.stringify({
                                                eventStartDate: start,
                                                eventEndDate: end
                                            })
                                        });

                                    if (response.ok) {
                                        const event = {
                                            title: `${start.slice(11, 16)} Slot Available`,
                                            start: start,
                                            end: end,
                                            color: '#28a745',
                                            className: 'slot-enabled'
                                        };
                                        calendar.addEvent(event);
                                    } else {
                                        alert('Failed to save slot.');
                                        this.checked = false;
                                    }
                                } else {
                                    // Optional: disable logic
                                }
                            });
                        });

                        document.getElementById('addEventSidebarLabel').innerText =
                            `Select Slot for ${selectedDate}`;
                        bsAddEventSidebar.show();
                    },
                    eventClick: function(info) {
                        const event = info.event;
                        const date = event.startStr.slice(0, 10); // Extract date

                        // Optional: call your sidebar modal here with all events on this date
                        fetch(`/calendar/slots-by-date?date=${date}`)
                            .then(res => res.json())
                            .then(slots => {
                                let html = '';
                                slots.forEach(slot => {
                                    html += `
                    <div class="border-bottom py-1">
                        <strong>${slot.start.slice(11, 16)} - ${slot.end.slice(11, 16)}</strong>
                        <span class="badge bg-${slot.is_active ? 'success' : 'secondary'} ms-2">${slot.is_active ? 'Enabled' : 'Disabled'}</span>
                    </div>
                `;
                                });

                                document.getElementById('slotDetailsContainer').innerHTML = html;
                                new bootstrap.Offcanvas('#slotDetailsSidebar').show();
                            });
                    },
                });

                calendar.render();
            }

            fetchSlots().then(data => {
                initializeCalendar(data);
            });
        });
    </script>
@endsection
