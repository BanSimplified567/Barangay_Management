<?php include 'header.php'; ?>

<h1>Dashboard</h1>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Total Residents</h5>
                <p class="card-text">1,234</p> <!-- Fetch from DB in controller -->
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Pending Certifications</h5>
                <p class="card-text">5</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Upcoming Events</h5>
                <p class="card-text">3</p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
