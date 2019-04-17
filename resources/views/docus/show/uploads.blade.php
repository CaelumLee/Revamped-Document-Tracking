<div id="upload" class="modal">
    <div class="modal-content">
        <div class="row">
            {!!Form::open(['action' => ['FileUploadsController@upload', $data['docu']->id], 
            'method' => 'POST', 'enctype' => 'multipart/form-data'])!!}
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
    <div class="card z-depth-3">
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo" style="font-size : 1.5em !important;">Uploads</a>    

                @if(!is_null($data['latest_route']) && $data['latest_route']->is_received == 1 
                && $data['latest_route']->has_sent == 0)
                    <ul id="nav-mobile" class="right">
                        <li><a class="modal-trigger" href="#upload"><i class="material-icons">edit</i></a></li>
                    </ul>
                @endif
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
                    @php($file_size = sizeof($data['file_uploads']))
                    @foreach($data['file_uploads'] as $file)
                        <tr>
                            <td>Upload {{$file_size}}</td>
                            <td>
                                <a href='#viewFiles' class='waves-effect waves-light btn-small btn-flat modal-trigger' 
                                id='view_files' data-upload_file_id = "{{$file->id}}">
                                    <i class='material-icons'>remove_red_eye</i>
                                </a>
                            </td>
                        </tr>
                        @php($file_size--)
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
