@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <span>Books Management</span>
                <button class="btn btn-sm btn-success" onclick="showModal(this)">+</button>
            </div>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Total Pages</th>
                        <th>Rating</th>
                        <th>ISBN</th>
                        <th>Published Date</th>
                        <th>Action</th>
                    </tr>
                <tbody>
                    @foreach($data as $item)
                        <tr>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->total_pages }}</td>
                            <td>{{ $item->rating }}</td>
                            <td>{{ $item->isbn }}</td>
                            <td>{{ $item->published_date }}</td>
                            <td>
                                <button class="btn btn-sm btn-info" style="marginRight: 10px"
                                    data-id="{{ $item->id }}" onclick="showModal(this)">
                                    Edit
                                </button>
                                @if (Auth::user()->role === 1)
                                    <button class="btn btn-sm btn-danger" style="marginRight: 10px"
                                        data-id="{{ $item->id }}" onclick="transaction(this, true)">
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
                <h5 class="modal-title">Book Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-data">
                    @csrf
                    <div class="form-group">
                        <label htmlFor="isbn">
                            ISBN <span style="color: red">*</span>
                        </label>
                        <input type="text" class="form-control" id="isbn" name="isbn" placeholder="ISBN" />
                    </div>
                    <div class="form-group">
                        <label htmlFor="title">
                            Title <span style="color: red">*</span>
                        </label>
                        <input type="text" class="form-control" id="title" name="title"
                            aria-describedby="title" placeholder="Title">
                    </div>
                    <div class="form-group">
                        <label htmlFor="total_pages">
                            Total Pages <span style="color: red">*</span>
                        </label>
                        <input type="number" class="form-control" id="total_pages" name="total_pages"
                            aria-describedby="total_pages" placeholder="Total Pages">
                    </div>
                    <div class="form-group">
                        <label htmlFor="author">
                            Authors <span style="color: red">*</span>
                        </label>
                        <select class="form-control" id="author" name="author" multiple="multiple" style="width: 100%"></select>
                    </div>
                    <div class="form-group">
                        <label htmlFor="rating">
                            Rating <span style="color: red">*</span>
                        </label>
                        <input type="number" min="0" class="form-control" id="rating" name="rating" placeholder="Rating" />
                    </div>
                    <div class="form-group">
                        <label htmlFor="published_date">
                            Published Date <span style="color: red">*</span>
                        </label>
                        <input type="date" class="form-control" id="published_date" name="published_date" placeholder="Published Date" />
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
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
            {id} = {...modal.data(), ...$(obj).data()},
            formResult = form.reduce((s, v) => ({...s, [v.name]: v.value, author: v.name === "author" ? [...s.author, v.value] : [...s.author]}), {author: []});
        try {
            const action = id ? (del ? window.axios.delete : window.axios.patch) : window.axios.post
            del ? deleteConfirm(() => action('{{ route('book.store') }}' + (id ? `/${id}` : ""))) : await action('{{ route('book.store') }}' + (id ? `/${id}` : ""), formResult)
            modal.modal('hide');
            return !del && window.location.reload()
        } catch {
            window.location.reload();
        }
    }

    const showModal = async obj => {
        $('#modal').modal('show')
        const { id } = $(obj).data(), form = $('#form-data').serializeArray();
        if (id) {
            try {
                const {
                    data
                } = await window.axios.get('{{route('book.store')}}/' + id)

                // Create New Pre-selected Option
                data.data.authors.map(({text, id}) => {
                    const option = new Option(text, id, true, true)
                    $('#author').append(option).trigger('change')
                })

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
        $('#author').select2({
            allowClear: true,
            placeholder: "Choose the author",
            multiple: true,
            ajax: {
                url: '{{route('author.find')}}',
                type: 'GET',
                data: ({term}) => ({keyword: term}),
                processResults: data => ({results: data.data})
            }
        })
        $('#modal').on('hide.bs.modal', () => {
            $('#form-data')[0].reset()
            $('#author').html(null).trigger('change');
        })
    })
</script>
@endsection
