@extends('layouts.app')

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="uk-container uk-container-small">
        <div class="col-md-8">   
            <a class="uk-button-small uk-button-primary" href=
                "#" onclick="showModal()" >Create new folder</a>      
            <div class="uk-card uk-card-default uk-card-hover uk-width-1-1 uk-margin-top">
                <div class="uk-grid-small uk-flex-middle" uk-grid>
                    <div class="uk-width-expand">
                        <ul>
                            <h3 class="uk-card-title uk-margin-remove-bottom">
                                Folders:   
                            </h3>
                            @foreach ($folders as $folder)
                                <li>
                                    <div class="vote-block" data-folder-id="{{$folder['id']}}">
                                        <a class="uk-button-small uk-button-primary" href="{{ route('home',['parent'=>$folder['id']]) }}">
                                            {{ $folder['name'] }}
                                        </a>
                                    </div>
                                </li>   
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="uk-card-header uk-padding-remove-bottom uk-background-muted">
                <form action="{{route('file.upload')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group uk-margin">
                        <input type="file" name="image">
                        <input type="hidden" id="folderId" name="folderId" value="{{$folderId}}">
                    </div> 
                    <button type="submit">Upload</button>   
                </form>
            </div>
            @foreach ($files as $file)
                <div class="uk-card uk-card-default uk-card-hover uk-width-1-1 uk-margin-top">
                    <div class="uk-card-body" id="file-{{$file['id']}}">
                        <div class="uk-grid-small uk-flex-middle" uk-grid>
                            <div class="uk-width-expand">
                                <h3 class="uk-card-title uk-margin-remove-bottom">
                                    {{$file['name']}}   
                                </h3>
                                @empty($file['status'])
                                    <div class="vote-block" data-file-id="{{$file['id']}}">
                                        <a class="uk-button-small uk-button-primary" href="#" onclick="clickVote(this, '1', '{{ $userId }}', '{{ csrf_token() }}')">Yes</a>
                                        <a class="uk-button-small uk-button-primary" href="#" onclick="clickVote(this, '2', '{{ $userId }}', '{{ csrf_token() }}')">No</a>
                                    </div>
                                @endempty
                            </div>
                        </div>
                        @foreach ($file['users'] as $user)
                            <div uk-grid>
                                <div class="uk-width-auto@m">
                                    {{$user['name']}}
                                </div>
                                <div class="uk-width-auto@m" name="status-{{$user['id']}}">
                                    {{$user['status'] == 1 ? 'Approved' : ($user['status'] == 2 ? 'Rejected' : 'Did not vote')}}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
        <!-- The Modal -->
    <div id="modal" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
            <p><span class="close" onclick="hideModal()">&times;</span></p>
            <form id="modal_form" action="{{ route('folder.add') }}" method="post" enctype="multipart/form-data">
            @csrf
                <input class="contact_input" id="folder-name" name="name" type="text" placeholder="Folder name" required pattern='[a-zа-яёїі0-9]/i' value="">
                <input type="hidden" id="folderId" name="folder_id" value="{{$folderId}}">
                <div class="button">
                    <a href="javascript:{}" type="submit" class="booking-send"  onclick="document.getElementById('modal_form').submit();">
                        Save
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ asset('css/my-style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/modal_popup.css') }}" rel="stylesheet">
@endsection

@section('js')
    <script src="{{ asset('js/file.blade.js') }}"></script>    
@endsection