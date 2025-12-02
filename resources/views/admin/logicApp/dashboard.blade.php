@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')

    <div class="dashboard-wrapper" >
        <!-- Animated Hero Header -->
        <div class="hero-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 style="font-size: 42px; font-weight: 900; margin-bottom: 10px;">
                        🚀 Dashboard
                    </h1>
                  

                    <p id="datetime" style="font-size: 14px; opacity: 0.8; margin-top: 5px;"></p>

                    <script>
                        const datetimeElement = document.getElementById('datetime');

                        function updateDateTime() {
                            const now = new Date();

                            const options = {
                                weekday: 'long',
                                month: 'long',
                                day: '2-digit',
                                year: 'numeric'
                            };
                            const dateStr = now.toLocaleDateString('en-US', options);

                            const hours = now.getHours();
                            const minutes = now.getMinutes();
                            const seconds = now.getSeconds();
                            const ampm = hours >= 12 ? 'PM' : 'AM';
                            const formattedHours = hours % 12 || 12;
                            const formattedMinutes = minutes.toString().padStart(2, '0');
                            const formattedSeconds = seconds.toString().padStart(2, '0');

                            const timeStr = `${formattedHours}:${formattedMinutes}:${formattedSeconds} ${ampm}`;

                            datetimeElement.innerHTML = `📅 ${dateStr} | 🕐 ${timeStr}`;
                        }

                        // Initial call + update every second
                        updateDateTime();
                        setInterval(updateDateTime, 1000);
                    </script>

                </div>




            </div>
        </div>



    </div>



@endsection
