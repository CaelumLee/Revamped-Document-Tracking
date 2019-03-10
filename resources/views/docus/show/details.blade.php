<?php
    use Carbon\Carbon;
?>
<div class="col s5">
    <div class="card z-depth-3">
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo hide-on-med-and-down" style="font-size : 1.5em !important;">Document Details</a>
                <a href="#" class="brand-logo hide-on-med-and-up" style="font-size : .9em !important;">Document Details</a>
                
                @if(Auth::user()->role->name == 'Admin')
                <ul id="nav-mobile" class="right">
                    <li><a href="{{route('docu.edit', ['id' => $data['docu']->id])}}"><i class="material-icons">edit</i></a></li>
                </ul>
                @endif
            </div>
        </nav>

        <div class="card-content">
            <table class="striped">
                <tr>
                    <td style="width:28%;" class = "blue white-text">Document type</td>
                    <td>{{$data['docu']->typeOfDocu->docu_type}}</td>
                </tr>

                <tr>
                    <td class = "blue white-text">Rushed Document</td>
                    @if($data['docu']->is_rush == 0)
                        <td>No</td>
                    @else
                        <td>Yes</td>
                    @endif
                </tr>

                <tr>
                    <td class = "blue white-text">Source type</td>
                    <?php use App\Department;
                        $add = $data['docu']->sender_address;
                        $dept = Department::where('name', $add)->first();
                    ?>
                    @if($dept == null)
                        <td>External</td>
                    @else
                        <td>{{$dept->source_type}}</td>
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
                    <td class = "blue white-text">Sender Details</td>
                    <td>{{$data['docu']->sender_name}} from {{$data['docu']->sender_address}}</td>
                </tr>

                <tr>
                    <td class = "blue white-text">Subject</td>
                    <td>{{$data['docu']->subject}}</td>
                </tr>

                <tr>
                    <td class = "blue white-text">Final Action Date</td>
                    <td>
                        {{Carbon::parse($data['docu']->final_action_date)->format('Y-m-d H:i:s a')}} &nbsp;
                        {{$diff_final_action_date}} days left
                    </td>
                </tr>

                <tr>
                    <td class = "blue white-text">ISO Code</td>
                    <td>{{$data['docu']->iso_code}}</td>
                </tr>

            </table>
        </div>

    </div>
</div>