@extends('layouts.admin.master')
@section('content')

<div class="row">
    <div class="col-md-10 offset-md-1">
        <div class="flex flex-row justify-content-center">

            <h2 class="text-center display-4">Bills Report</h2>

            <form action="/admin/reports/exports" class="my-5" method="POST">
                @csrf
                @method('POST')
            
                <div class="row mb-4">
                    <div class="col-6">
                        <div class="form-group">
                            <label>From :</label>

                            <div class="input-group date" id="reservationReportBillFrom"
                             data-target-input="nearest">
                                <input name="from_report" type="text"
                                 class="form-control"
                                 data-target="#reservationReportBillFrom" 
                                 data-inputmask-alias="datetime" data-inputmask-inputformat="mm/yyyy" data-mask
                                 value=""
                                 placeholder="MM/YYYY"
                                 />
                                <div class="input-group-append" data-target="#reservationReportBillFrom"
                                 data-toggle="datetimepicker">
                                 <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                            
                                
                            </div>
                            @if($errors->any())
                                <p style="color: red">{{$errors->first('from_report')}}</p>
                            @endif
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>To :</label>
                            <div class="input-group date" id="reservationReportBillTo"
                            data-target-input="nearest">
                               <input name="to_report" type="text"
                                class="form-control"
                                data-target="#reservationReportBillTo" 
                                data-inputmask-alias="datetime" data-inputmask-inputformat="mm/yyyy" data-mask
                                value=""
                                placeholder="MM/YYYY"
                                />
                               <div class="input-group-append" data-target="#reservationReportBillTo"
                                data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                               </div>
                           
                               
                            </div>
                            @if($errors->any())
                               <p style="color: red">{{$errors->first('to_report')}}</p>
                            @endif
                       
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 flex justify-content-center">
                        <input type="submit" role="button" value="Download" class="btn btn-success" style="width:100%">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection