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
                                        <a class="uk-button-small uk-button-primary" href="#" onclick="clickVote(this, '1')">Yes</a>
                                        <a class="uk-button-small uk-button-primary" href="#" onclick="clickVote(this, '2')">No</a>
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
        <div class="modal-content" id='folder' data-value="{{$folderId}}">
            <p><span class="close" onclick="hideModal()">&times;</span></p>
            <input class="contact_input" id="folder-name" name="folder-name" type="text" placeholder="Folder name" required="required" value="">
            <p id="folder-name-error" class="valid-error"></p>
            <div class="button">
                <a class="booking-send" href="#" onclick="saveFolder('{{ route('folder.add') }}')">
                    Save
                </a>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ asset('css/my-style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/modal_popup.css') }}" rel="stylesheet">
@endsection

@section('js')    

    <script type="text/javascript">
        async function clickVote(element, status) {
            let url = `/file/vote`;
            let userId = "{{ $userId }}";
            let voteBlock = element.closest('.vote-block');
            let fileId = voteBlock.getAttribute('data-file-id');
            let statusText = (status==='1') ? 'Approved' : 'Rejected';

            try {
                const response = await fetch(url, {
                    method: "post",
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        'vote': status,
                        'user_id': userId,
                        'file_id': fileId
                    })
                });

                const result = await response.json();

                if (result.success) {

                    voteBlock.parentNode.removeChild(voteBlock);  

                    let statusTeg = document.getElementById(`file-${fileId}`).querySelector(`div[name="status-${userId}"]`);

                    statusTeg.textContent = statusText;
                }

            } catch (error) {
                
            }
        }
    </script>
    <script type="text/javascript">
        var errorTag = document.getElementById('folder-name-error');
        var errorMessage = 'The folder name cannot be empty and must consist only of letters and numbers!';
        var nameTag = document.getElementById('folder-name')
        var modal = document.getElementById('modal')

        function showModal() {
            document.getElementById('modal').style.display = "block";
        }

        function hideModal() {
            errorTag.style.display = "none";
            nameTag.value = '';
            modal.style.display = "none";
        }

        nameTag.onfocus = function() {
            errorTag.style.display = "none";
        };

        async function saveFolder(url) {
            let folderId = document.getElementById('folder').getAttribute('data-value');
            let name = nameTag.value.trim();

            if (folderId.length == 0) {
                folderId = null;
            }

            let st = new RegExp('[^а-яА-ЯЁёІіЇїa-zA-Z0-9]+');

            if (name.length < 1 || name.length > 255 || st.test(name)) {
                
                errorTag.textContent = errorMessage;
                errorTag.style.display = "block";
                return;
            }

            try {
                const response = await fetch(url, {
                    method: "post",
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        'folder_id': folderId,
                        'name': name
                    })  
                });

                hideModal();

            } catch (error) {

                console.log(error);               
            }
        }
    </script>
@endsection