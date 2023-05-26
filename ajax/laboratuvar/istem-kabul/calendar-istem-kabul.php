<link href='assets/fullcalendar5/lib/main.css' rel='stylesheet'/>
<script src='assets/fullcalendar5/lib/main.js'></script>
<link href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css' rel='stylesheet'>
<?php include "../../../controller/fonksiyonlar.php";
date_default_timezone_set('Europe/Istanbul');
$simdikitarih = date('Y/m/d H:i:s');
$kullanici_id = $_SESSION['id'];
$islem=$_GET["islem"];

if ($islem=="istem-kabul-calendar"){?>
    <script>
        $( document ).ready(function() {
            setTimeout(function(){
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    editable: true,
                    navLinks: true,
                    eventLimit: true,
                    timeZone: 'local',
                    selectable: true,
                    selectHelper: true,
                    locale: 'tr',
                    themeSystem: 'bootstrap5',

                    buttonText: {
                        today:    'Bugün',
                        month:    'Ay',
                        week:     'Hafta',
                        day:      'Gün',
                        list:     'Liste',
                        listMonth: 'Aylık Liste',
                        listYear: 'Yıllık Liste',
                        listWeek: 'Haftalık Liste',
                        listDay: 'Günlük Liste'
                    },

                    initialView: 'dayGridMonth',

                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },

                    events: {
                        url: '/ajax/laboratuvar/istem-kabul/sql-istemkabul.php?islem=sql_lab_istem_hasta_randevu_calendar',
                        method: 'POST',
                        extraParams: {
                            custom_param1: 'something',
                            custom_param2: 'somethingelse'
                        },
                        failure: function() {
                            alert('Hata');
                        },
                        color: '',
                        textColor: 'black',
                        className:'bg-yesil text-white'
                    },

                    eventClick: function(info) {
                        // var date =  (new Date(info.event.start)).toISOString().slice(0,16);
                        alert(info.event.title);


                    }
                });

                calendar.render();
                //    calendar.refetchEvents()
            }, 100);
        });
    </script>
<?php } ?>