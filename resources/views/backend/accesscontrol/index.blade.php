@extends('backend.layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">

                    <h5>Access Control List | <strong>ACL</strong></h5>
                    <div class="ibox-tools"></div>
                </div>
                <div class="ibox-content">
                    <form class="m-t" role="form" method='GET' action="{{ route('backend.accesscontrol.index') }}">
                        @csrf
                        <div class="form-group">
                            <label for="id">{{ __('Role User') }}</label>
                            <select
                                id="id"
                                name="id"
                                class="form-control{{ $errors->has('id') ? ' is-invalid' : '' }}"
                                parsley-trigger="change"
                                required
                                onchange="this.form.submit()"
                            >
                                <option value="" disabled selected>{{ __('Choose Role') }}</option>

                                @foreach ($roles as $role)

                                    @if ( request()->get('id') && Crypt::decrypt(request()->get('id')) == $role->id)
                                        <option value="{{ Crypt::encrypt($role->id) }}" selected>{{ $role->name }}</option>
                                    @else
                                        <option value="{{ Crypt::encrypt($role->id) }}">{{ $role->name }}</option>
                                    @endif

                                @endforeach
                            </select>
                        </div>
                    </form>

                    <div id="nestable-menu">
                        <button type="button" data-action="expand-all" class="btn btn-white btn-sm">Expand All</button>
                        <button type="button" data-action="collapse-all" class="btn btn-white btn-sm">Collapse All</button>
                    </div>

                    <form method='POST' role="form" action="{{ route('backend.accesscontrol.update') }}">
                        @csrf
                        <input type="hidden" name="role" id="role"/>
                        <div class="dd" id="nestable2">
                            <ol class="dd-list">

                                @foreach($accesscontrols->menus as $menu)
                                    @if($menu->parent == config('setting.value.zero'))
                                        @if($menu->route == config('setting.url.crash'))
                                            <li class="dd-item godfather-li" data-id="{{ $menu->id }}">
                                                <div class="dd-handle">
                                                    <input
                                                        type="checkbox"
                                                        name="menus[]"
                                                        id="{{ $menu->id }}"
                                                        value="{{ $menu->id }}"
                                                        class="i-checks"
                                                        {{ (in_array($menu->id, $accesscontrols->pivot)) ? 'Checked' : '' }}
                                                    />
                                                    {{ $menu->label }}
                                                </div>
                                                <ol class="dd-list">

                                                    @foreach($accesscontrols->menus as $menu2)
                                                        @if($menu2->parent == $menu->id)

                                                            <li class="dd-item parents-li-{{ $menu->id }} parent-li" data-id="{{ $menu2->id }}">
                                                                <div class="dd-handle">
                                                                    <input
                                                                        type="checkbox"
                                                                        name="menus[]"
                                                                        id="{{ $menu2->id }}"
                                                                        value="{{ $menu2->id }}"
                                                                        class="i-checks"
                                                                        {{ (in_array($menu2->id, $accesscontrols->pivot)) ? 'Checked' : '' }}
                                                                    />
                                                                    {{ $menu2->label }}
                                                                </div>
                                                                <ol class="dd-list">
                                                                    @foreach($accesscontrols->menus as $menu3)
                                                                        @if($menu3->parent == $menu2->id)

                                                                            <li class="dd-item child-li-{{ $menu2->id }}" data-id="{{ $menu3->id }}">
                                                                                <div class="dd-handle">
                                                                                    <input
                                                                                        type="checkbox"
                                                                                        name="menus[]"
                                                                                        id="{{ $menu3->id }}"
                                                                                        value="{{ $menu3->id }}"
                                                                                        class="i-checks"
                                                                                        {{ (in_array($menu3->id, $accesscontrols->pivot)) ? 'Checked' : '' }}
                                                                                    />
                                                                                    {{ $menu3->label }}
                                                                                </div>
                                                                            </li>
                                                                        @endif
                                                                    @endforeach
                                                                </ol>
                                                            </li>

                                                        @endif
                                                    @endforeach

                                                </ol>
                                            </li>
                                        @else
                                            <li class="dd-item" data-id="{{ $menu->id }}">
                                                <div class="dd-handle">
                                                    <style>.m-r-xs { margin-right: 7px; }</style>
                                                    <i class="m-r-xs text-muted fa fa-ellipsis-h"></i>
                                                    <input
                                                        type="checkbox"
                                                        name="menus[]"
                                                        id="{{ $menu->id }}"
                                                        value="{{ $menu->id }}"
                                                        class="i-checks"
                                                        {{ (in_array($menu->id, $accesscontrols->pivot)) ? 'Checked' : '' }}
                                                    />
                                                    {{ $menu->label }}
                                                </div>
                                            </li>
                                        @endif
                                    @endif

                                @endforeach
                            </ol>
                        </div>

                        <button type="submit"
                                class="pull-right btn btn-md waves-effect waves-light btn-success m-b-5 update-access-control"
                                data-toggle="tooltip"
                                data-placement="top"
                        >
                            <i class="fa fa-pencil"></i>
                            <strong>UPDATE</strong>
                        </button>

                        <div class="clearfix"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('onpage-js')

    @include('backend.layouts.message')

    <script>
        $(document).ready(function () {
            $('.i-checks').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
            });

            // activate Nestable for list 2
            $('#nestable2').nestable({
                group: 1,
                maxDepth: 0
            }).nestable('collapseAll');

            $('#nestable-menu').on('click', function (e) {
                var target = $(e.target),
                    action = target.data('action');
                if (action === 'expand-all') {
                    $('.dd').nestable('expandAll');
                }
                if (action === 'collapse-all') {
                    $('.dd').nestable('collapseAll');
                }
            });

            $('.update-access-control').on('click',function(e){
                e.preventDefault();

                $('#role').val( $('#id').val() );
                if(!$('#role').val())
                {
                    swal({
                        title: "Warning",
                        text: "Pick a Role !",
                        type: "warning"
                    });
                    return false;
                }

                var form = $(this).parents('form');

                swal({
                    title: "Are you sure?",
                    text: "This will affected to user in this role",
                    type: "info",
                    showCancelButton: true,
                    confirmButtonClass: 'btn-primary waves-effect waves-light',
                    confirmButtonText: 'Update!',
                    closeOnConfirm: true,
                    dangerMode: true,
                }, function (isConfirm) {
                    if (isConfirm) {
                        form.submit();
                        $('.update-access-control').attr('disabled', 'disabled');
                    } else {
                        swal("Canceled !");
                        $('.update-access-control').removeAttr('disabled');
                    }
                });
            });

            $('.godfather-li input[name="menus[]"]').on('ifChecked', function (e) {
                var parent_id = $(this).attr("id");
                $('.parents-li-'+parent_id+' input[name="menus[]"]').iCheck('check');
            })
            $('.godfather-li input[name="menus[]"]').on('ifUnchecked', function (e) {
                var parent_id = $(this).attr("id");
                $('.parents-li-'+parent_id+' input[name="menus[]"]').iCheck('uncheck');
            })
            $('.parent-li input[name="menus[]"]').on('ifChecked', function (e) {
                var parent_id = $(this).attr("id");
                $('.child-li-'+parent_id+' input[name="menus[]"]').iCheck('check');
            })
            $('.parent-li input[name="menus[]"]').on('ifUnchecked', function (e) {
                var parent_id = $(this).attr("id");
                $('.child-li-'+parent_id+' input[name="menus[]"]').iCheck('uncheck');
            })

        });
    </script>
@endsection
