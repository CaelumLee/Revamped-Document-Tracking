<div id="upload" class="modal">
    <div class="modal-content">
        <div class="row">
            {!!Form::open(['action' => ['FileUploadsController@upload'], 
            'method' => 'POST', 'enctype' => 'multipart/form-data'])!!}
            <input type="hidden" id="docu_id" name = "docu_id" value = "{{$data['docu']->id}}">
            <div class="file-field input-field">
                <div class="btn">
                    <span>File</span>
                    <input type="file" name="filename[]" multiple>
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="Upload one or more files">
                </div>
            </div>

        </div>
    </div>
    
    <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-red btn red">Cancel</a>
        {{Form::submit('Upload', ['class'=>'btn green'])}}
        {!! Form::close() !!} 
    </div>
</div>

<div id="viewFiles" class="modal">
    <div id="File_to_place" class="modal-content">
    Loading...
    </div>

    <div class="modal-footer">
        <a href="#!" id="fileViewer-close-button" class="modal-close waves-effect waves-red red btn-flat">Go back</a>
    </div>
</div>

<div class="col s7">
    <div class="card">
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo" style="font-size : 1.5em !important;">Uploads</a>    

                @foreach($data['transactions'] as $t)
                    @if($t->recipient == Auth::user()->id 
                    && $t->is_received == 1 && $t->has_sent == 0)
                        <ul id="nav-mobile" class="right">
                            <li><a class="modal-trigger" href="#upload"><i class="material-icons">edit</i></a></li>
                        </ul>
                    @endif
                @endforeach
            </div>
        </nav>

        <div class="card-content">
            <table class="stripped">
                <thead>
                    <tr class = "blue white-text">
                        <th>Uploads</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                
                <tbody>
                    @foreach($data['file_uploads'] as $key => $file)
                        <tr>
                            <td>Upload {{$key + 1}}</td>
                            <td>
                                <a href='#viewFiles' class='waves-effect waves-light btn-small btn-flat modal-trigger' 
                                id='view_files'>
                                    <i class='material-icons'>remove_red_eye</i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
