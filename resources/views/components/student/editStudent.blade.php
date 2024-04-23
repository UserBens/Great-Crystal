@extends('layouts.admin.master')
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- general form elements -->
                <div>
                    <form method="POST" action={{ route('student.update', $data->student->id) }}
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Student</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="studentName">Name<span style="color: red">*</span></label>
                                        <input name="studentName" type="text" class="form-control" id="studentName"
                                            placeholder="Enter name"
                                            value="{{old('name') ? old('name'):  $data->student->name }}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('email')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <label for="studentId_or_passport">ID/Passport Number<span
                                                style="color: red">*</span></label>
                                        <input name="studentId_or_passport" type="text" class="form-control"
                                            id="studentId_or_passport" placeholder="Enter ID/Passport"
                                            value="{{old('id_or_passport')? old('id_or_passport') : $data->student->id_or_passport}}"
                                            required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('id_or_passport')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Gender<span style="color: red">*</span></label>
                                            <select name="studentGender" class="form-control" required>
                                                @php
                                                $arrGender = array('Female', 'Male');
                                                $genderStudent = old('gender')? old('gender') : $data->student->gender;
                                                @endphp

                                                <option selected>
                                                    {{ old('gender')? old('gender') : $data->student->gender}}</option>

                                                @if ($data->student->gender || old('gender'))

                                                @foreach($arrGender as $gender)

                                                @if ($genderStudent !== $gender)
                                                <option>{{$gender}}</option>
                                                @endif

                                                @endforeach
                                                @else
                                                <option>Male</option>
                                                <option>Female</option>
                                                @endif
                                            </select>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('gender')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">

                                        <div class="form-group">
                                            <label>Religion<span style="color: red">*</span></label>
                                            <select name="studentReligion" class="form-control" required>

                                                @php
                                                $arrReligion = array('Islam', 'Protestant Christianity', 'Catholic
                                                Christianity', 'Hinduism', 'Buddhism', 'Confucianism', 'Others');
                                                $student_religion = old('religion') ? old('religion') :
                                                $data->student->religion;
                                                @endphp

                                                <option selected>
                                                    {{ old('religion')? old('religion') : $data->student->religion}}
                                                </option>




                                                @if ($data->student->religion || old('religion'))

                                                @foreach($arrReligion as $religion)

                                                @if ($student_religion !== $religion)
                                                <option>{{$religion}}</option>
                                                @endif

                                                @endforeach
                                                @else
                                                @foreach($arrReligion as $religion)
                                                <option>{{$religion}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('religion')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Grade<span style="color: red">*</span></label>

                                            @php


                                            $selectedId = old('grade_id')? old('grade_id') : $data->student->grade_id;
                                            $selectedName = '';
                                            $option = [];

                                            foreach($data->allGrade as $value) {
                                            # code...
                                            if( (int)$selectedId === (int)$value->id)
                                            {
                                            $selectedName = $value->name .' - '. $value->class;
                                            }

                                            else {
                                            array_push($option, $value);
                                            }
                                            }
                                            @endphp

                                            <select name="gradeId" class="form-control" required>

                                                <option selected value="{{$selectedId}}">{{$selectedName}}</option>



                                                @foreach($option as $value)

                                                <option value="{{$value->id}}">{{$value->name. ' - ' .$value->class}}
                                                </option>

                                                @endforeach

                                            </select>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('gender')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="nisn">NISN</label>
                                        <input name="nisn" type="text" class="form-control" id="nisn"
                                            placeholder="Enter nisn"
                                            value="{{old('nisn')? old('nisn') : $data->student->nisn}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('nisn')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <br>
                                <br>
                                <br>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label>Date of Birth<span style="color: red">*</span></label>
                                        <div class="input-group date" id="reservationdate" data-target-input="nearest">
                                            <input name="studentDate_birth" type="text"
                                                class="form-control datetimepicker-input" placeholder="dd/mm/yyyy"
                                                data-target="#reservationdate" data-inputmask-alias="datetime"
                                                data-inputmask-inputformat="dd/mm/yyyy" data-mask
                                                value="{{old('date_birth') ? date("d/m/Y", strtotime(old('date_birth'))) : date("d/m/Y", strtotime($data->student->date_birth))}}"
                                                required />

                                            <div class="input-group-append" data-target="#reservationdate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('date_birth')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <label for="studentNationality">Nationality<span
                                                style="color: red">*</span></label>
                                        <input name="studentNationality" type="text" class="form-control"
                                            id="studentNationality" placeholder="Enter nationality"
                                            value="{{old('nationality')? old('nationality') : $data->student->nationality}}"
                                            required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('nationality')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="studentPlace_birth">Place of Birth<span
                                                style="color: red">*</span></label>
                                        <input name="studentPlace_birth" type="text" class="form-control"
                                            id="studentPlace_birth" placeholder="Enter city"
                                            value="{{old('place_birth')? old('place_birth') : $data->student->place_birth}}"
                                            required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('place_birth')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">

                                        <label for="studentPlace_of_issue">Place of issue</label>
                                        <input name="studentPlace_of_issue" type="text" class="form-control" @php
                                            $place_of_issue=$data->student->place_of_issue?
                                        $data->student->place_of_issue : ''
                                        @endphp

                                        id="studentPlace_of_issue"
                                        value="{{old('place_of_issue')? old('place_of_issue') : $place_of_issue}}"
                                        placeholder="Enter place">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('place_of_issue')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Date of Register</label>
                                            <div class="input-group date"
                                                data-target-input="nearest">
                                                <input name="created_at" type="text" class="form-control "
                                                    placeholder="dd/mm/yyyy" data-mask readonly
                                                    value="{{old('created_at') ? date("d/m/Y", strtotime(old('created_at'))) : date("d/m/Y", strtotime($data->student->created_at))}}" />
                                                <div class="input-group-append"
                                                    data-target="#reservationdateStudentDateExp"
                                                    data-toggle="datetimepicker">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                </div>
                                            </div>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('created_at')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">

                                        <label>Date of Expiry</label>
                                        <div class="input-group date" id="reservationdateStudentDateExp"
                                            data-target-input="nearest">
                                            <input name="studentDate_exp" type="text"
                                                class="form-control datetimepicker-input" placeholder="dd/mm/yyyy"
                                                data-target="#reservationdateStudentDateExp"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy"
                                                data-mask @php $date_exp=$data->student->date_exp ? date("d/m/Y",
                                            strtotime(old($data->student->date_exp))) : '';
                                            @endphp

                                            value="{{old('date_exp') ? date("d/m/Y", strtotime(old('date_exp'))) : $date_exp}}"
                                            />
                                            <div class="input-group-append" data-target="#reservationdateStudentDateExp"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>

                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('date_exp')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body students -->


                        @php
                        foreach ($data->student->relationship as $key => $value) {
                        if( strtolower($value->relation) === 'father')
                        {
                        $father = $value;
                        } else {
                        $mother = $value;
                        }
                        }
                        @endphp

                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Father's</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="fatherName">Name<span style="color: red">*</span></label>
                                        <input name="fatherName" type="text" class="form-control" id="fatherName"
                                            placeholder="Enter father's name"
                                            value="{{old('father_name') ? old('father_name') : $father->name}}"
                                            required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('father_name')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-6">

                                        <label for="fatherId_or_passport">ID / Passport Number<span
                                                style="color: red">*</span></label>
                                        <input name="fatherId_or_passport" type="text" class="form-control"
                                            id="fatherId_or_passport" placeholder="Enter father's ID/Passport"
                                            value="{{old('father_id_or_passport') ? old('father_id_or_passport') : $father->id_or_passport}}"
                                            required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('father_id_or_passport')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="fatherOccupation">Occupation</label>
                                        <input name="fatherOccupation" type="text" class="form-control"
                                            id="fatherOccupation" placeholder="Enter father's occupation"
                                            value={{old('father_occupation') ? old('father_occupation') : $father->occupation}}>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('father_occupation')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">


                                        <label>Religion<span style="color: red">*</span></label>
                                        <select name="fatherReligion" class="form-control" required>
                                            @php
                                            $arrReligion = array('Islam', 'Protestant Christianity', 'Catholic
                                            Christianity', 'Hinduism', 'Buddhism', 'Confucianism', 'Others');
                                            $father_religion = old('father_religion')? old('father_religion') :
                                            $father->religion;
                                            @endphp

                                            <option selected> {{ $father_religion }}</option>

                                            @if ($father_religion)

                                            @foreach($arrReligion as $religion)

                                            @if ($father_religion !== $religion)
                                            <option>{{$religion}}</option>
                                            @endif

                                            @endforeach
                                            @else
                                            @foreach($arrReligion as $religion)
                                            <option>{{$religion}}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('father_religion')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">


                                    <div class="col-md-4">


                                        <label for="fatherPlace_birth">Place of Birth<span
                                                style="color: red">*</span></label>
                                        <input name="fatherPlace_birth" type="text" class="form-control"
                                            id="fatherPlace_birth" placeholder="Enter city"
                                            value="{{old('father_place_birth')? old('father_place_birth') : $father->place_birth}}"
                                            required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('father_place_birth')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label>Date of Birth<span style="color: red">*</span></label>
                                        <div class="input-group date" id="reservationFatherBirthDate"
                                            data-target-input="nearest">

                                            <input name="fatherBirth_date" type="text"
                                                class="form-control datetimepicker-input" placeholder="dd/mm/yyyy"
                                                data-target="#reservationFatherBirthDate"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy"
                                                data-mask required
                                                value="{{old('father_date_birth') ? date("d-m-Y", strtotime(old('father_date_birth'))) : date("d-m-Y", strtotime($father->date_birth))}}" />
                                            <div class="input-group-append" data-target="#reservationFatherBirthDate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>

                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('father_date_birth')}}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">


                                        <label for="fatherNationality">Nationality<span
                                                style="color: red">*</span></label>
                                        <input name="fatherNationality" type="text" class="form-control"
                                            id="fatherNationality" placeholder="Enter nationality"
                                            value="{{old('father_nationality')? old('father_nationality') : $father->nationality }}"
                                            required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('father_nationality')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <br>
                                <br>

                                <div class="form-group">
                                    <label for="fatherCompany_name">Company Name</label>
                                    <input name="fatherCompany_name" type="text" class="form-control"
                                        id="fatherCompany_name" placeholder="Enter company name"
                                        value="{{old('father_company_name')}}">
                                    @if($errors->any())
                                    <p style="color: red">{{$errors->first('father_company_name')}}</p>
                                    @endif
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="fatherCompany_address">Company Address</label>
                                        <input name="fatherCompany_address" type="text" class="form-control"
                                            id="fatherCompany_address" placeholder="Enter company address"
                                            value="{{old('father_company_address')? old('father_company_address') : $father->company_address}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('father_company_address')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">

                                        <label for="fatherCompany_phone">Company Phone</label>
                                        <input name="fatherCompany_phone" type="text" class="form-control"
                                            id="fatherCompany_phone" placeholder="Enter company phone"
                                            value="{{old('father_phone')? old('father_phone') : $father->phone}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('father_phone')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <br>
                                <br>

                                <div class="form-group">
                                    <label for="fatherHome_address">Home Address<span
                                            style="color: red">*</span></label>
                                    <input name="fatherHome_address" type="text" class="form-control"
                                        id="fatherHome_address" placeholder="Enter name" required
                                        value="{{old('father_home_address') ? old('father_home_address') : $father->home_address}}">
                                    @if($errors->any())
                                    <p style="color: red">{{$errors->first('father_home_address')}}</p>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="fatherTelephhone">Telephone</label>
                                        <input name="fatherTelephhone" type="text" class="form-control"
                                            id="fatherTelephhone" placeholder="Enter telephone"
                                            value="{{old('father_telephone')? old('father_telephone') : $father->telephone}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('father_telephone')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="fatherMobilephone">Mobilephone<span
                                                style="color: red">*</span></label>
                                        <input name="fatherMobilephone" type="text" class="form-control"
                                            id="fatherMobilephone" placeholder="Enter mobilephone" required
                                            value="{{old('father_mobilephone')? old('father_mobilephone') : $father->mobilephone}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('father_mobilephone')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="fatherMobilephone">Email<span style="color: red">*</span></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input name="fatherEmail" type="email" class="form-control"
                                                placeholder="Enter father's email"
                                                value="{{old('father_email')? old('father_email') : $father->email}}"
                                                required>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('father_email')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body father -->


                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Mother's</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="motherName">Name<span style="color: red">*</span></label>
                                        <input name="motherName" type="text" class="form-control" id="motherName"
                                            placeholder="Enter mother's name"
                                            value="{{old('mother_name')? old('mother_name') : $mother->name}}" required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('mother_name')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-6">

                                        <label for="motherId_or_passport">ID / Passport Number<span
                                                style="color: red">*</span></label>
                                        <input name="motherId_or_passport" type="text" class="form-control"
                                            id="motherId_or_passport" placeholder="Enter mother's ID/Passport"
                                            value="{{old('mother_id_or_passport')? old('mother_id_or_passport') : $mother->id_or_passport}}"
                                            required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('mother_id_or_passport')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="motherOccupation">Occupation</label>
                                        <input name="motherOccupation" type="text" class="form-control"
                                            id="motherOccupation" placeholder="Enter mother's occupation"
                                            value="{{old('mother_occupation')? old('mother_occupation') : $mother->occupation}}">
                                    </div>
                                    <div class="col-md-6">


                                        <label>Religion<span style="color: red">*</span></label>
                                        <select name="motherReligion" class="form-control" required>
                                            @php
                                            $arrReligion = array('Islam', 'Protestant Christianity', 'Catholic
                                            Christianity', 'Hinduism', 'Buddhism', 'Confucianism', 'Others');
                                            $mother_religion = old('mother_religion')? old('mother_religion') :
                                            $mother->religion;
                                            @endphp

                                            <option selected>{{$mother_religion}}</option>

                                            @if ($mother_religion)

                                            @foreach($arrReligion as $religion)

                                            @if ($mother_religion !== $religion)
                                            <option>{{$religion}}</option>
                                            @endif

                                            @endforeach
                                            @else
                                            @foreach($arrReligion as $religion)
                                            <option>{{$religion}}</option>
                                            @endforeach
                                            @endif
                                        </select>

                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('mother_religion')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">


                                    <div class="col-md-4">


                                        <label for="motherPlace_birth">Place of Birth<span
                                                style="color: red">*</span></label>
                                        <input name="motherPlace_birth" type="text" class="form-control"
                                            id="motherPlace_birth" placeholder="Enter city"
                                            value="{{old('mother_place_birth')? old('mother_place_birth') : $mother->place_birth}}"
                                            required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('mother_place_birth')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label>Date of Birth<span style="color: red">*</span></label>
                                        <div class="input-group date" id="reservationMotherBirthDate"
                                            data-target-input="nearest">
                                            <input name="motherBirth_date" type="text"
                                                class="form-control datetimepicker-input" placeholder="dd/mm/yyyy"
                                                data-target="#reservationMotherBirthDate"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy"
                                                data-mask
                                                value="{{old('mother_date_birth') ? date("d/m/Y", strtotime(old('mother_date_birth'))) : date("d/m/Y", strtotime($mother->date_birth))}}"
                                                required />
                                            <div class="input-group-append" data-target="#reservationMotherBirthDate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>

                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('mother_date_birth')}}</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">


                                        <label for="motherNationality">Nationality<span
                                                style="color: red">*</span></label>
                                        <input name="motherNationality" type="text" class="form-control"
                                            id="motherNationality" placeholder="Enter nationality"
                                            value="{{old('mother_nationality')? old('mother_nationality') : $mother->nationality}}"
                                            required>
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('mother_nationality')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <br>
                                <br>

                                <div class="form-group">
                                    <label for="motherCompany_name">Company Name</label>
                                    <input name="motherCompany_name" type="text" class="form-control"
                                        value="{{old('mother_company_name')? old('mother_company_name') : $mother->company_name}}"
                                        id="motherCompany_name" placeholder="Enter company name">

                                    @if($errors->any())
                                    <p style="color: red">{{$errors->first('mother_company_name')}}</p>
                                    @endif
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="motherCompany_address">Company Address</label>
                                        <input name="motherCompany_address" type="text" class="form-control"
                                            id="motherCompany_address" placeholder="Enter company address"
                                            value="{{old('mother_company_address')? old('mother_company_address') : $mother->company_address}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('mother_company_address')}}</p>
                                        @endif
                                    </div>
                                    <div class="col-md-6">

                                        <label for="motherCompany_phone">Company Phone</label>
                                        <input name="motherCompany_phone" type="text" class="form-control"
                                            id="motherCompany_phone" placeholder="Enter company phone"
                                            value="{{old('mother_phone')? old('mother_phone') : $mother->phone}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('mother_phone')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <br>
                                <br>

                                <div class="form-group">
                                    <label for="motherHome_address">Home Address<span
                                            style="color: red">*</span></label>
                                    <input name="motherHome_address" type="text" class="form-control"
                                        id="motherHome_address" placeholder="Enter name"
                                        value="{{old('mother_home_address')? old('mother_home_address') : $mother->home_address}}"
                                        required>
                                    @if($errors->any())
                                    <p style="color: red">{{$errors->first('mother_home_address')}}</p>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="motherTelephhone">Telephone</label>
                                        <input name="motherTelephhone" type="text" class="form-control"
                                            id="motherTelephhone" placeholder="Enter telephone"
                                            value="{{old('mother_telephone')? old('mother_telephone') : $mother->telephone}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('mother_telephone')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="motherMobilephone">Mobilephone<span
                                                style="color: red">*</span></label>
                                        <input name="motherMobilephone" type="text" class="form-control"
                                            id="motherMobilephone" placeholder="Enter mobilephone" required
                                            value="{{old('mother_mobilephone')? old('mother_mobilephone') : $mother->mobilephone}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('mother_mobilephone')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label for="motherEmail">Email<span style="color: red">*</span></label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                            </div>
                                            <input name="motherEmail" type="email" class="form-control"
                                                placeholder="Enter father's email"
                                                value="{{old('mother_email')? old('mother_email') : $mother->email}}"
                                                required>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('mother_email')}}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body Mother -->

                        <div class="card card-dark">
                            <div class="card-header">
                                <h3 class="card-title">Brother's or sister's
                                </h3>
                            </div>

                            @php
                            $brotherSister1 = sizeof($data->brother_or_sisters) > 0 ? $data->brother_or_sisters[0] :
                            null;
                            $brotherSister2 = sizeof($data->brother_or_sisters) > 1 ? $data->brother_or_sisters[1] :
                            null;
                            $brotherSister3 = sizeof($data->brother_or_sisters) > 2 ? $data->brother_or_sisters[2] :
                            null;
                            $brotherSister4 = sizeof($data->brother_or_sisters) > 3 ? $data->brother_or_sisters[3] :
                            null;
                            $brotherSister5 = sizeof($data->brother_or_sisters) > 4 ? $data->brother_or_sisters[4] :
                            null;
                            @endphp
                            <!-- /.card-header -->
                            <!-- form start -->
                            <div class="card-body">
                                <div class="form-group row">

                                    <div class="col-md-4">
                                        <label for="brotherOrSisterName1">Name</label>
                                        <input name="brotherOrSisterName1" type="text" class="form-control"
                                            id="brotherOrSisterName1" placeholder="Enter brother's or sister's name"
                                            value="{{old('brotherOrSisterName1')? old('brotherOrSisterName1') : ($brotherSister1? $brotherSister1->name : '') }}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('brotherOrSisterName1')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label>Date of Birth</label>
                                        <div class="input-group date" id="reservationBrotherOrSisterBirthDate1"
                                            data-target-input="nearest">
                                            <input name="brotherOrSisterBirth_date1" type="text"
                                                class="form-control datetimepicker-input" placeholder="dd/mm/yyyy"
                                                data-target="#reservationBrotherOrSisterBirthDate1"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy"
                                                data-mask
                                                value="{{old('brotherOrSisterBirth_date1') ? date("d/m/Y", strtotime(old('brotherOrSisterBirth_date1'))) : ($brotherSister1 ? date("d/m/Y", strtotime($brotherSister1->date_birth)) : '')}}" />
                                            <div class="input-group-append"
                                                data-target="#reservationBrotherOrSisterBirthDate1"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>

                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('brotherOrSisterBirth_date1')}}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="brotherOrSisterGrade1">Grade</label>
                                        <input name="brotherOrSisterGrade1" type="text" class="form-control"
                                            id="brotherOrSisterGrade1" placeholder="Enter grade brother's or sister's"
                                            value="{{old('brotherOrSisterGrade1') ? old('brotherOrSisterGrade1') : ($brotherSister1? $brotherSister1->grade : '')}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('brotherOrSisterGrade1')}}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">

                                    <div class="col-md-4">
                                        <label for="brotherOrSisterName2">Name</label>
                                        <input name="brotherOrSisterName2" type="text" class="form-control"
                                            id="brotherOrSisterName2" placeholder="Enter brother's or sister's name"
                                            value="{{old('brotherOrSisterName2')? old('brotherOrSisterName2') : ($brotherSister2? $brotherSister2->name : '') }}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('brotherOrSisterName2')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label>Date of Birth</label>
                                        <div class="input-group date" id="reservationBrotherOrSisterBirthDate2"
                                            data-target-input="nearest">
                                            <input name="brotherOrSisterBirth_date2" type="text"
                                                class="form-control datetimepicker-input" placeholder="dd/mm/yyyy"
                                                data-target="#reservationBrotherOrSisterBirthDate2"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy"
                                                data-mask
                                                value="{{old('brotherOrSisterBirth_date2') ? date("d/m/Y", strtotime(old('brotherOrSisterBirth_date2'))) : ($brotherSister2 ? date("d/m/Y", strtotime($brotherSister2->date_birth)) : '')}}" />
                                            <div class="input-group-append"
                                                data-target="#reservationBrotherOrSisterBirthDate2"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('brotherOrSisterBirth_date2')}}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="brotherOrSisterGrade2">Grade</label>
                                        <input name="brotherOrSisterGrade2" type="text" class="form-control"
                                            id="brotherOrSisterGrade2" placeholder="Enter grade brother's or sister's"
                                            value="{{old("brotherOrSisterGrade2")? old("brotherOrSisterGrade2") : ($brotherSister2? $brotherSister2->grade : '')}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('brotherOrSisterGrade2')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="brotherOrSisterName3">Name</label>
                                        <input name="brotherOrSisterName3" type="text" class="form-control"
                                            id="brotherOrSisterName3" placeholder="Enter brother's or sister's name"
                                            value="{{old('brotherOrSisterName3')? old('brotherOrSisterName3') : ($brotherSister3? $brotherSister3->name : '') }}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('brotherOrSisterName3')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label>Date of Birth</label>
                                        <div class="input-group date" id="reservationBrotherOrSisterBirthDate3"
                                            data-target-input="nearest">
                                            <input name="brotherOrSisterBirth_date3" type="text"
                                                class="form-control datetimepicker-input" placeholder="dd/mm/yyyy"
                                                data-target="#reservationBrotherOrSisterBirthDate3"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy"
                                                data-mask
                                                value="{{old('brotherOrSisterBirth_date3') ? date("d/m/Y", strtotime(old('brotherOrSisterBirth_date3'))) : ($brotherSister3 ? date("d/m/Y", strtotime($brotherSister3->date_birth)) : '')}}" />
                                            <div class="input-group-append"
                                                data-target="#reservationBrotherOrSisterBirthDate3"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>

                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('brotherOrSisterBirth_date3')}}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="brotherOrSisterGrade3">Grade</label>
                                        <input name="brotherOrSisterGrade3" type="text" class="form-control"
                                            id="brotherOrSisterGrade3" placeholder="Enter grade brother's or sister's"
                                            value="{{old('brotherOrSisterGrade3') ? old('brotherOrSisterGrade3') : ($brotherSister3? $brotherSister3->grade : '')}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('brotherOrSisterGrade3')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="brotherOrSisterName4">Name</label>
                                        <input name="brotherOrSisterName4" type="text" class="form-control"
                                            id="brotherOrSisterName4" placeholder="Enter brother's or sister's name"
                                            value="{{old('brotherOrSisterName4')? old('brotherOrSisterName4') : ($brotherSister4? $brotherSister4->name : '') }}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('brotherOrSisterName4')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label>Date of Birth</label>
                                        <div class="input-group date" id="reservationBrotherOrSisterBirthDate4"
                                            data-target-input="nearest">
                                            <input name="brotherOrSisterBirth_date4" type="text"
                                                class="form-control datetimepicker-input" placeholder="dd/mm/yyyy"
                                                data-target="#reservationBrotherOrSisterBirthDate4"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy"
                                                data-mask
                                                value="{{old('brotherOrSisterBirth_date4') ? date("d/m/Y", strtotime(old('brotherOrSisterBirth_date4'))) : ($brotherSister4 ? date("d/m/Y", strtotime($brotherSister4->date_birth)) : '')}}" />
                                            <div class="input-group-append"
                                                data-target="#reservationBrotherOrSisterBirthDate4"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('brotherOrSisterBirth_date4')}}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="brotherOrSisterGrade4">Grade</label>
                                        <input name="brotherOrSisterGrade4" type="text" class="form-control"
                                            id="brotherOrSisterGrade4" placeholder="Enter grade brother's or sister's"
                                            value="{{old('brotherOrSisterGrade4') ? old('brotherOrSisterGrade4') : ($brotherSister4? $brotherSister4->grade : '')}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('brotherOrSisterGrade4')}}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-4">
                                        <label for="brotherOrSisterName5">Name</label>
                                        <input name="brotherOrSisterName5" type="text" class="form-control"
                                            id="brotherOrSisterName5" placeholder="Enter brother's or sister's name"
                                            value="{{old('brotherOrSisterName5')? old('brotherOrSisterName5') : ($brotherSister5? $brotherSister5->name : '') }}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('brotherOrSisterName5')}}</p>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label>Date of Birth</label>
                                        <div class="input-group date" id="reservationBrotherOrSisterBirthDate5"
                                            data-target-input="nearest">
                                            <input name="brotherOrSisterBirth_date5" type="text"
                                                class="form-control datetimepicker-input" placeholder="dd/mm/yyyy"
                                                data-target="#reservationBrotherOrSisterBirthDate5"
                                                data-inputmask-alias="datetime" data-inputmask-inputformat="dd/mm/yyyy"
                                                data-mask
                                                value="{{old('brotherOrSisterBirth_date5') ? date("d/m/Y", strtotime(old('brotherOrSisterBirth_date5'))) : ($brotherSister5 ? date("d/m/Y", strtotime($brotherSister5->date_birth)) : '')}}" />
                                            <div class="input-group-append"
                                                data-target="#reservationBrotherOrSisterBirthDate5"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            @if($errors->any())
                                            <p style="color: red">{{$errors->first('brotherOrSisterBirth_date5')}}
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="brotherOrSisterGrade5">Grade</label>
                                        <input name="brotherOrSisterGrade5" type="text" class="form-control"
                                            id="brotherOrSisterGrade5" placeholder="Enter grade brother's or sister's"
                                            value="{{old('brotherOrSisterGrade5') ? old('brotherOrSisterGrade5') : ($brotherSister5? $brotherSister5->grade : '')}}">
                                        @if($errors->any())
                                        <p style="color: red">{{$errors->first('brotherOrSisterGrade5')}}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <!-- /.card-body Brother or sisters -->

                <div class="d-flex justify-content-center my-5">
                    <button type="submit" class="col-11 btn btn-success">Update Student Profile</button>
                </div>
                </form>

            </div>
            <!-- /.card -->

            <!-- general form elements -->
        </div>
        <!--/.col (right) -->

    </div>
    <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
@endsection
