<?php
    use App\User;
    use App\Department;
?>
<div class="col s12">
    <div class="card">
        <nav>
            <div class="nav-wrapper">
                <a href="#" class="brand-logo" style="font-size : 1.5em !important;">Transaction</a>    
            </div>
        </nav>

    <div class="card-content">
        <table class="stripped">
            <thead>
                <tr class = "blue white-text">
                    <th>Location</th>
                    <th>Person in charge</th>
                    <th>Route</th>
                    <th>To</th>
                    <th>Remarks</th>
                    <th>Date deadline</th>
                    <th>Has received?</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['transactions'] as $transaction)
                    <tr>
                        <th>{{Department::whereId($transaction->location)
                        ->first()
                        ->acronym}}</th>
                        <th>{{User::whereId($transaction->in_charge)
                        ->first()
                        ->username}}</th>
                        <th>{{Department::whereId($transaction->route)
                        ->first()
                        ->acronym}}</th>
                        <th>{{User::whereId($transaction->recipient)
                        ->first()
                        ->username}}</th>
                        <th>{{$transaction->remarks}}</th>
                        <th>{{$transaction->date_deadline}}</th>
                        <th>
                            @if($transaction->is_received)
                                Yes
                            @else
                                No
                            @endif
                        </th>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    </div>
</div>