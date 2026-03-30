<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>User List</h1>
        <a href="./?page=user/create" class="btn btn-primary"><i class="bi bi-person-add"></i> Create User</a>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-hover">
        <tr>
            <th>ID</th>
            <th>Photo</th>
            <th>Name</th>
            <th>Option</th>
        </tr>
        <?php
        $users = getUsers();
        $count = 1;
        while ($row = $users->fetch_object()) {
            echo '<tr>
                <td>' . $count++ . '</td>
                <td> <img src ="' . ($row->photo ?? './assets/image/emptyUser.jpg') . '" class="rounded img-thumbnail" style="max-width: 200px">
                </td>
                <td>' . $row->name . '</td>
                <td>
        <a href="./?page=user/update&id=' . $row->id . '" role="button" class="btn btn-primary"><i class="bi bi-pencil-square"></i>Update</a>
        <a href="./?page=user/delete&id=' . $row->id . '" role="button" class="btn btn-danger"><i class="bi bi-trash3"></i>Delete</a>
        </td>
        </tr>';
            $count++;
        }
        ?>
    </table>
</div>
<script>
    $(document).ready(function () {
        $('.btn-danger').click(function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {

                if (result.isConfirmed) {
                    window.location.href = href;
                    Swal.fire({
                        title: "Deleted!",
                        text: "Your file has been deleted.",
                        icon: "success"
                    });
                };
            });
        });
    })

</script>