<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>User List</h1>
        <a href="./?page=user/create" class="btn btn-primary">Create User</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <tr>
            <th>#</th>
            <th>Photo</th>
            <th>Name</th>
        </tr>
        <?php
        $users = getUsers();
        $count = 1;
        while ($row = $users->fetch_object()) {
            echo '<tr>
                <th>' . $count . '</th>
                <th>' . $row->photo . '</th>
                <th>' . $row->name . '</th>
            </tr>';
            $count++;
        }
        ?>
    </table>
</div>