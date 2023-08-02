<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


// FOR SIMPLICITY WE WON'T IMPLEMENT DELETE OPERATIONS.

/*
 * TODO: Get all students list. ALREADY IMPLEMENTED IN THE DEMO SESSION.
 * URL: GET /students
 * Response:
     Status code: 200
     JSON body:
         {
           "data": [
              {
                "id": "student_id",
                "name": "student_name",
                "email": "student_email",
                "phone": "student_phone"
              },
              {
                "id": "student_id",
                "name": "student_name",
                "email": "student_email",
                "phone": "student_phone"
              }
           ]
        }
 */
Route::get('/students', function (Request $request) {
    $rawData = DB::select(DB::raw("select id, name, email, phone from students"));

    $responseData = [];

    foreach ($rawData as $rd) {
        array_push($responseData, [
            'id' => $rd->id,
            'name' => $rd->name,
            'email' => $rd->email,
            'phone' => $rd->phone,
        ]);
    }

    $statusCode = 200;

    return response()->json([
            'data' => $responseData
    ], $statusCode);
});

Route::post('/students', function (Request $request) {
//getting data
  $user_name=$request->input('name');
  $user_email=$request->input('email');
  $user_phone=$request->input('phone');
//inserting in Database
DB::statement("INSERT INTO students (name, email, phone) VALUES ('$user_name','$user_email','$user_phone')");

//Retriving data from database
$rawData = DB::select(DB::raw("SELECT id FROM students WHERE email = '$user_email'"));

//formulating output
$statusCode = 200;
return response()->json([
    'data' => $rawData[0]
],$statusCode);
});


/*
    * TODO: Create new student.
    * URL: POST /students
    * Request Body:
        {
            "name": "student_name",
            "email": "student_email",
            "phone": "student_phone"
        }
    * Response:
        status_code: 200
        JSON body:
            {
                "data": {
                    "id": "student_id_from_database"
                }
            }
*/
Route::get('/students/{id}', function (Request $request,int $id) {
    //retriving row corresponding to id
    $rawData = DB::select(DB::raw("SELECT id, name, email, phone FROM students WHERE id=$id"));

    //checking if the id does exist
    if(empty($rawData[0])){
        $statusCode = 404;
    }

   //formulating output
    $statusCode = 200;
    return response()->json([
            'data' => $rawData[0]
    ], $statusCode);
});
/*
    * TODO: Get student details by id
    * URL: GET /students/{id}
    * Response:
       * success:
            status_code: 200
            JSON body:
                {
                    "data": {
                        "id": "student_id",
                        "name": "student_name",
                        "email": "student_email",
                        "phone": "student_phone"
                    }
                }
       * not found:
            status_code: 404
            JSON body:
                {
                    "data": {}
                }
*/


Route::put('/students/{id}', function (Request $request,int $id) {
    //getting input from user
    $new_user_name=$request->input('name');
    $new_user_email=$request->input('email');
    $new_user_phone=$request->input('phone');

    //updating database with new data
    DB::statement("UPDATE students SET name='$new_user_name', email='$new_user_email', phone='$new_user_phone' WHERE id=$id");

    //getting new data with select statement
    $rawData = DB::select(DB::raw("select id, name, email, phone from students where id=$id"));

   //formulating output
    $statusCode = 200;
    return response()->json([
            'data' => $rawData[0]
    ], $statusCode);
});

/*
    * TODO: Update student data
    * URL: PUT /students/{id}
    * Request Body:
        {
            "name": "new_student_name",
            "email": "new_student_email",
            "phone": "new_student_phone"
        }
    * Response:
        status_code: 200
        JSON body:
            {
                "data": {
                    "id": "student_id",
                    "name": "new_student_name",
                    "email": "new_student_email",
                    "phone": "new_student_phone"
                }
            }
 */


 /*
   * TODO: For Courses implement Get, Create & Update endpoints same as students
   * Get all URL: GET /courses
   * Get course details: GET /courses/{id}
   * Create new course: POST /courses{id}
   * Update course: PUT /courses/{id}
   * Note: For JSON keys in both the request and response, let's use the same database columns names.
 */
Route::get('/courses', function (Request $request) {
    $rawData = DB::select(DB::raw("select id, name from courses"));

    $responseData = [];

    foreach ($rawData as $rd) {
        array_push($responseData, [
            'id' => $rd->id,
            'name' => $rd->name,
        ]);
    }

    $statusCode = 200;

    return response()->json([
            'data' => $responseData
    ], $statusCode);
});


Route::get('/courses/{id}', function (Request $request,int $id) {
    //retriving row corresponding to id
    $rawData = DB::select(DB::raw("SELECT id, name FROM courses WHERE id=$id"));

    //checking if the id does exist
    if(empty($rawData[0])){
        $statusCode = 404;
    }

   //formulating output
    $statusCode = 200;
    return response()->json([
            'data' => $rawData[0]
    ], $statusCode);
});


Route::post('/courses', function (Request $request) {
    //getting data
      $course_name=$request->input('name');

    //inserting in Database
    DB::statement("INSERT INTO courses (name) VALUES ('$course_name')");

    //Retriving data from database
    $rawData = DB::select(DB::raw("SELECT id FROM courses WHERE name = '$course_name'"));

    //formulating output
    $statusCode = 200;
    return response()->json([
        'data' => $rawData[0]
    ],$statusCode);
    });

Route::put('/courses/{id}', function (Request $request,int $id) {
    //getting input from user
    $new_course_name=$request->input('name');

    //updating database with new data
    DB::statement("UPDATE courses SET name='$new_course_name' WHERE id=$id");

    //getting new data with select statement
    $rawData = DB::select(DB::raw("select id, name from courses where id=$id"));

   //formulating output
    $statusCode = 200;
    return response()->json([
            'data' => $rawData[0]
    ], $statusCode);
});




Route::get('/grades', function (Request $request) {
    $rawData = DB::select(DB::raw("select student_id, course_id, grade from grades"));
    $responseData = [];

    foreach ($rawData as $rd) {
        array_push($responseData, [
            'student_id' => $rd->student_id,
            'course_id' => $rd->course_id,
            'grade' =>$rd->grade,
        ]);
    }

    $statusCode = 200;

    return response()->json([
            'data' => $responseData
    ], $statusCode);
});



 /*
  * TODO: Get all grades endpoint
  * URL: GET /grades
  * Response:
        status_code: 200
        JSON body: {
            "data": [
                {
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                },
                {
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                }
            ]
        }
  */

  Route::get('/students/{student_id}/grades', function (Request $request,int $student_id) {

    //getting data with select statement
    $rawData = DB::select(DB::raw("SELECT student_id,course_id,grade FROM grades WHERE student_id=$student_id"));
    $responseData = [];
    foreach ($rawData as $rd){
        array_push($responseData,[
        'student_id' => $rd->student_id,
        'course_id' => $rd->course_id,
        'grade' => $rd->grade,
        ]
        );
    }

   //formulating output
    $statusCode = 200;
    return response()->json([
            'data' => $responseData
    ], $statusCode);
});
  /*
   * TODO: Get grades for specific student only.
   * URL: GET /students/{student_id}/grades
   * Response:
        status_code: 200
        JSON body: {
            "data": [
                {
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                },
                {
                    "student_id": "STUDENT ID"
                    "course_id": "COURSE ID",
                    "grade": "GRADE"
                }
            ]
        }
  */

  Route::get('/students/{student_id}/grades/{grade_id}', function (Request $request,int $student_id,int $grade_id) {

    //getting data with select statement
    $rawData = DB::select(DB::raw("SELECT student_id,course_id,grade FROM grades WHERE student_id=$student_id AND id=$grade_id"));


   //formulating output
    $statusCode = 200;
    return response()->json([
            'data' => $rawData[0]
    ], $statusCode);
});
  /*
   * TODO: Get specific grade for specific student only. Shall return one record only if exists.
   * URL: GET /students/{student_id}/grades/{grade_id}
   * Response:
        status_code: 200
        JSON body: {
            "data": {
                "student_id": "STUDENT ID"
                "course_id": "COURSE ID",
                "grade": "GRADE"
            }
        }
  */
