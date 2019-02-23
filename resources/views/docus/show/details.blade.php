<div id="edit_docu" class="modal">
    <div class="modal-content">
        
    </div>
    <div class="modal-footer">
      <a href="#!" class="modal-close waves-effect waves-green btn-flat">Agree</a>
    </div>
</div>

<div class="col s5">
    <div class="card">
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo hide-on-med-and-down" style="font-size : 1.5em !important;">Document Details</a>
                <a href="#" class="brand-logo hide-on-med-and-up" style="font-size : .9em !important;">Document Details</a>
                
                @if(Auth::user()->role->name == 'Admin')
                <ul id="nav-mobile" class="right">
                    <li><a class="modal-trigger" href="#edit_docu"><i class="material-icons">edit</i></a></li>
                </ul>
                @endif
            </div>
        </nav>

        <div class="card-content">
            <table class="striped">
                <tr>
                    <td style="width:30%;" class = "blue white-text">Document type</td>
                    <td>{{$data['docu']->docu_type}}</td>
                </tr>

                <tr>
                    <td class = "blue white-text">Source type</td>
                    @if($data['docu']->source_type == null)
                        <td>External</td>
                    @else
                        <td>{{$data['docu']->source_type}}</td>
                    @endif
                </tr>

                <tr>
                    <td class = "blue white-text">Confidentiality</td>
                    @if($data['docu']->confidentiality == 1)
                        <td>Admin Level</td>
                    @else
                        <td>All Levels</td>
                    @endif
                </tr>

                <tr>
                    <td class = "blue white-text">Complexity</td>
                    <td>{{$data['docu']->complexity}}</td>
                </tr>

                <tr>
                    <td class = "blue white-text">Recipients</td>
                    <td>@TODO</td>
                </tr>

                <tr>
                    <td class = "blue white-text">Subject</td>
                    <td>{{$data['docu']->subject}}</td>
                </tr>

                <tr>
                    <td class = "blue white-text">Final Action Date</td>
                    <td>{{$data['docu']->final_action_date}}</td>
                </tr>

                <tr>
                    <td class = "blue white-text">ISO Code</td>
                    <td>{{$data['docu']->iso_code}}</td>
                </tr>

            </table>
        </div>

    </div>
</div>