<?php include '../auth/auth.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Calendar</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nepali-date-converter@3.3.1/dist/nepali-date-converter.umd.min.js"></script>

    <!-- jQuery + SweetAlert -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
    body {
        background: #076c8b67;
    }

    .calendar-card {
        max-width: 1000px;
        margin: 20px auto;
        border-radius: 12px;
    }

    #calendar {
        height: 80vh;
    }

    .fc-daygrid-day-top {
        display: flex !important;
        flex-direction: column;
        align-items: center;
         gap: 2px;
    }

    .fc-daygrid-day-number {
        font-weight: 600;
        font-size: 13px;
        text-align: center;
        color: #000;
    }

    .bs-date {
        margin-top: 2px;
        text-align: center;
    }

.fc-day-today {
    background: transparent !important; /* remove default yellow */
}

.fc-day-today {
    background-color: #076c8b4d !important; /* light blue */
    border: 1px solid #0d6efd;
    border-radius: 6px;
}

.fc-day-today .fc-daygrid-day-number {
    color: #000 !important;
    font-weight: 800;
}

.fc-day-sat .fc-daygrid-day-number {
    color: #b10516 !important; 
    font-weight: 800;
}


</style>
</head>

<body>

<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand">Calendar Dashboard</span>

        <div class="text-white">
            Welcome, <?php echo $_SESSION['name']; ?>
            <button onclick="window.location='dashboard.php'" class="btn btn-sm btn-light ms-3">
                Back
            </button>
        </div>
    </div>
</nav>

<div class="container">

    <div class="card shadow calendar-card p-3">

        <div class="d-flex justify-content-between align-items-center ">
            <h7> Event Calendar</h7>

            <small class="text-muted">
                Click date to add • Click event to edit
            </small>
        </div>

        <hr>

        <div id="calendar"></div>

    </div>

</div>

<script src="../assets/js/calendar.js"></script>

</body>
</html>