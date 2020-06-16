@extends('layouts.app')

@section('content')
    <div class="uk-container uk-container-small">
        <div class="col-md-8">        
            <div class="uk-card-header uk-padding-remove-bottom uk-background-muted">
                <p uk-margin>
                    <a class="uk-button-small uk-button-primary" href=
                    "#"" >Add file</a>
                    <a class="uk-button-small uk-button-primary" href=
                    "#"" >Create folder</a>
                </p>
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
@endsection