@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <span>Authors Management</span>
                <button class="btn btn-sm btn-success" onclick="showModal(this)">+</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Fullname</th>
                        <th>Action</th>
                    </tr>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->first_name . ' ' . $item->middle_name . ' '. $item->last_name }}
                            </td>
                            <td>
                                <button class="btn btn-sm btn-info" style="marginRight: 10px"
                                    data-id="{{ $item->id }}" onclick="showModal(this)">
                                    Edit
                                </button>
                                @if (Auth::user()->role === 1)
                                    <button class="btn btn-sm btn-danger" style="marginRight: 10px" data-id="{{ $item->id }}" onclick="transaction(this, true)">
                                        Delete
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </thead>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" id="modal" aria-labelledby="modal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Author Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-data">
                    @csrf
                    <div class="form-group">
                        <label htmlFor="first_name">
                            First Name <span style="color: red">*</span>
                        </label>
                        <input type="text" class="form-control" id="first_name" name="first_name"
                            aria-describedby="first_name" placeholder="First Name">
                    </div>
                    <div class="form-group">
                        <label htmlFor="middle_name">
                            Middle Name <span style="color: red">*</span>
                        </label>
                        <input type="text" class="form-control" id="middle_name" name="middle_name"
                            aria-describedby="middle_name" placeholder="Middle Name">
                    </div>
                    <div class="form-group">
                        <label htmlFor="last_name">
                            Last Name <span style="color: red">*</span>
                        </label>
                        <input type="text" class="form-control" id="last_name" name="last_name"
                            placeholder="Last Name" />
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="transaction(this)">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script type="text/javascript">
    const deleteConfirm = (func) => {
        return Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.value) {
                Swal.fire(
                    'Deleted!',
                    'Your file has been deleted.',
                    'success'
                ).then(async () => {
                    await func()
                    window.location.reload()
                })
            }
        })
    }
    
    const transaction = async (obj, del = false) => {
        const form = $('#form-data').serializeArray(),
            modal = $('#modal'),
            {
                id
            } = Object.assign({}, modal.data(), $(obj).data()),
            formResult = form.reduce((s, v) => ({
                ...s,
                [v.name]: v.value
            }), {})
        try {
            const action = id ? (del ? window.axios.delete : window.axios.patch) : window.axios.post
            del ? deleteConfirm(() => action('{{ route('author.store') }}' + (id ? `/${id}` : ""))) : await action('{{ route('author.store') }}' + (id ? `/${id}` : ""), formResult)
            modal.modal('hide');
            return !del && window.location.reload()
        } catch {
            window.location.reload();
        }
    }

    const showModal = async obj => {
        $('#modal').modal('show')
        const {
            id
        } = $(obj).data(),
            form = $('#form-data').serializeArray()
        if (id) {
            try {
                const {
                    data
                } = await window.axios.get('{{ url()->to('/') }}/api/author/' + id)
                form.map(item => {
                    $('#' + item.name).val(data.data[item.name])
                })
                $('#modal').data('id', id)
            } catch {
                console.error('.:: Kesalahan Parsing Data')
            }
        } else {
            $('#modal').data('id', '')
        }
    }

    $(document).ready(() => {
        $('#modal').on('hide.bs.modal', () => {
            $('#form-data')[0].reset()
        })
    })

</script>
@endsection
