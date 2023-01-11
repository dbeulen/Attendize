<form class="online_payment ajax" action="<?php echo route('postCreateOrder', ['event_id' => $event->id]); ?>" method="post">
    <div class="online_payment">
        <div class="row">
            <div class="col-md-12">
                <p>Druk op onderstaande knop om af te rekenen via IDEAL of Creditcard</p>
            </div>
        </div>
		<div class="row">
            <div class="col-md-12">
                <img class=""src="{{asset('assets/images/public/EventPage/credit-card-logos.png')}}"/>
            </div>
        </div>
        

        {!! Form::token() !!}

        <input class="btn btn-lg btn-success card-submit" style="width:100%;" type="submit" value="@lang("Public_ViewEvent.complete_payment")">
    </div>
</form>

