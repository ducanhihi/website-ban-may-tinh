@extends('layout.app')

@section('content')
    <main id="main" class="main">

        <div class="pagetitle">
            <h1>Quản Lí Người Dùng</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{route('admin.home')}}">Home</a></li>
                    <li class="breadcrumb-item active">Người Dùng</li>
                </ol>
            </nav>
            <div class="col-lg-12">
                <div class="card card-table">

                    <div class="card-body">
                        <table id="users_table" data-page-length='5'
                               class="table table-sm">
                            <thead>
                            <tr>

                                <th class="text-center fw-bold">ID</th>
                                <th class="text-center fw-bold">Tên</th>
                                <th class="text-center fw-bold">Email</th>
                                <th class="text-center fw-bold">SĐT</th>
                                <th class="text-center fw-bold">Address</th>
                                <th class="text-center fw-bold">Ngày sinh</th>
                                <th class="text-center fw-bold">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($allUsers as $user)
                                <tr>
                                    <td class="text-center fw-bold">{{$user-> id}}</td>
                                    <td class="text-center fw-bold">{{$user-> name}}</td>
                                    <td class="text-center fw-bold">{{$user-> email}}</td>
                                    <td class="text-center fw-bold">{{$user-> phone}}</td>
                                    <td class="text-center fw-bold">{{$user-> address}}</td>
                                    <td class="text-center fw-bold">{{$user-> DOB}}</td>
                                    <td class="d-flex justify-content-around align-content-center">
                                        <a href="#deleteUser"
                                           data-id="{{$user->id}}"
                                           class="btn btn-sm btn-outline-danger delete-user"
                                           data-toggle="modal"
                                           data-toggle="tooltip"
                                           title="Xóa">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <!-- Add Modal HTML -->
    <div id="addUserModal" class="modal fade" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="/admin/create/category" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Add Category</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required="">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
                        <input type="submit" class="btn btn-success" value="Add">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Brand Modal -->
    <div id="deleteUser" class="modal fade">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="/home/user/" method="POST" id="deleteUserForm">
                    @method('DELETE')
                    @csrf
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Xác nhận xóa
                        </h5>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-hidden="true">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body p-4">
                        <p class="mb-3">Bạn có chắc chắn muốn xóa tài khoản này?</p>
                        <p class="text-danger mb-0"><small>Hành động này không thể hoàn tác và có thể ảnh hưởng đến các bên liên quan.</small></p>
                    </div>
                    <div class="modal-footer border-top-0">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i>Hủy
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt mr-1"></i>Xóa
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="eJOY__extension_root" class="eJOY__extension_root_class" style="all: unset;"></div>
    </body>
    <script>
        $(document).on("click", ".delete", function () {
            var OrderId = $(this).data('id');
            $("#deleteOrderModal form").attr('action', '/home/category/' + OrderId);
        });
        $(document).on("click", ".edit", function () {
            var editID = $(this).data('id');
            $("#editOrderModal form").attr('action', '/admin/edit/category/' + editID);
        });
    </script>
    <script>
        $(document).ready(function () {
            $('.edit').on('click', function () {
                var id = $(this).data('id');
                var name = $(this).data('name');

                $('#editOrderModal input[name="id"]').val(id);
                $('#editOrderModal input[name="name"]').val(name);
            });
        });
    </script>
    <script>
        let brandTable = new DataTable('#users_table', {
            pageLength: 5,
            responsive: true,
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ mục",
                info: "Hiển thị _START_ đến _END_ của _TOTAL_ mục",
                infoEmpty: "Hiển thị 0 đến 0 của 0 mục",
                infoFiltered: "(lọc từ _MAX_ mục)",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Sau",
                    previous: "Trước"
                }
            },
            drawCallback: function(settings) {
                $('#showing-entries-user').text(settings._iDisplayLength);
            }
        });

        // Xử lý xóa thương hiệu
        $(document).on("click", ".delete-user", function () {
            var userId = $(this).data('id');
            $("#deleteUserForm").attr('action', '/home/user/' + userId);
        });
    </script>
@endsection
