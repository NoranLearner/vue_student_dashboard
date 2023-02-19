<template>

    <!-- ************************************************  -->

    <div>
        <h1 class="text-center"> CRUD PHP , Vuejs.3 & MySQL </h1>
    </div>

    <!-- ************************************************  -->

    <!-- Alert For Database Connection -->
    <div class="container" v-if="!isDBConnected">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ isDBConnectedMsg }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
        </div>
    </div>

    <!-- ************************************************  -->

    <div class="container" v-else>

        <!-- ************************************************  -->

        <!-- Show Add Student Button -->
        <div class="col-lg-12">
            <button class="btn btn-danger float-start" v-if="studentsData.length > 0" data-bs-toggle="modal"
                data-bs-target="#deleteAllModal">
                <font-awesome-icon :icon="['fas', 'user-times']" />
                Delete All Students
            </button>
            <button type="button" class="btn btn-info float-end" data-bs-toggle="modal" data-bs-target="#overlay">
                <font-awesome-icon :icon="['fas', 'user']" />
                Add New Student
            </button>
            <div class="clearfix"></div>
        </div>

        <!-- ************************************************  -->

        <hr class="bg-info">

        <!-- ************************************************  -->

        <!-- Show Error Message -->

        <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="errorMsg">
            {{ errorMsg }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
        </div>

        <!-- ************************************************  -->

        <!-- Show Success Message -->

        <div class="alert alert-success alert-dismissible fade show" role="alert" v-if="successMsg">
            {{ successMsg }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
        </div>

        <!-- ************************************************  -->

        <!-- Show Student Details : Tables -->

        <table class="table table-border table-striped caption-top">

            <caption>List of Registered Students ({{ studentsData.length }})</caption>

            <thead>
                <tr class="bg-success text-light">
                    <th>
                        <font-awesome-icon :icon="['fas', 'id-badge']" />
                        ID
                    </th>
                    <th>
                        <font-awesome-icon :icon="['fas', 'user']" />
                        Name
                    </th>
                    <th>
                        <font-awesome-icon :icon="['fas', 'envelope']" />
                        Email
                    </th>
                    <th>
                        <font-awesome-icon :icon="['fas', 'phone']" />
                        Phone
                    </th>
                    <th>
                        <font-awesome-icon :icon="['fas', 'gear']" />
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="(student, i) in studentsData" :key="i">
                    <td>{{ student.id }}</td>
                    <td>{{ student.name }}</td>
                    <td>{{ student.email }}</td>
                    <td>{{ student.phone}}</td>
                    <td>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                            @click="selectStudent(student)">
                            <font-awesome-icon :icon="['fas', 'user-xmark']" />
                            Delete
                        </button>
                        &nbsp;
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal"
                            @click="selectStudent(student)">
                            <font-awesome-icon :icon="['fas', 'user-pen']" />
                            Update
                        </button>
                    </td>
                </tr>
            </tbody>

        </table>

        <!-- ************************************************  -->

        <!-- Show Add New Student Modal -->

        <div id="overlay" class="modal fade" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" v-if="showAddModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-info" id="exampleModalLabel">Add New Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="#" method="post" @click.prevent>
                            <div class="form-floating mb-3" :class="{ 'form-group-error': v$.newStudent.name.$error }">
                                <input type="text" class="form-control" id="StudentNameFloat" placeholder="John Doe"
                                    v-model.trim="newStudent.name">
                                <label for="StudentNameFloat">
                                    <font-awesome-icon :icon="['far', 'user']" />
                                    Student Name
                                </label>
                                <span class="error-feedback" v-if="v$.newStudent.name.$error">
                                    {{ v$.newStudent.name.$errors[0].$message }}
                                </span>
                            </div>
                            <div class="form-floating mb-3" :class="{ 'form-group-error': v$.newStudent.email.$error }">
                                <input type="email" class="form-control" id="StudentEmailFloat"
                                    placeholder="John_Doe@gmail.com" v-model.trim="newStudent.email">
                                <label for="StudentEmailFloat">
                                    <font-awesome-icon :icon="['far', 'envelope']" />
                                    Student Email
                                </label>
                                <span class="error-feedback" v-if="v$.newStudent.email.$error">
                                    {{ v$.newStudent.email.$errors[0].$message }}
                                </span>
                            </div>
                            <div class="form-floating mb-3" :class="{ 'form-group-error': v$.newStudent.phone.$error }">
                                <input type="tel" class="form-control" id="StudentPhoneFloat" placeholder="0123456789"
                                    v-model.trim="newStudent.phone">
                                <label for="StudentPhoneFloat">
                                    <font-awesome-icon :icon="['fas', 'phone']" />
                                    Student Phone No.
                                </label>
                                <span class="error-feedback" v-if="v$.newStudent.phone.$error">
                                    {{ v$.newStudent.phone.$errors[0].$message }}
                                </span>
                            </div>
                            <hr class="bg-info">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info" @click="addNewStudent()">Add New Student</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ************************************************  -->

        <!-- Show Edit Student Modal -->

        <div id="updateModal" class="modal fade" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true" v-if="showUpdateModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-warning" id="updateModalLabel">Update Current Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="#" method="post" @click.prevent>
                            <div class="form-floating mb-3" :class="{ 'form-group-error': v$.currentStudent.name.$error }">
                                <input type="text" class="form-control" id="StudentNameFloat" placeholder="John Doe"
                                    v-model.trim="currentStudent.name">
                                <label for="StudentNameFloat">
                                    <font-awesome-icon :icon="['far', 'user']" />
                                    Student Name
                                </label>
                                <span class="error-feedback" v-if="v$.currentStudent.name.$error">
                                    {{ v$.currentStudent.name.$errors[0].$message }}
                                </span>
                            </div>
                            <div class="form-floating mb-3" :class="{ 'form-group-error': v$.currentStudent.email.$error }">
                                <input type="email" class="form-control" id="StudentEmailFloat"
                                    placeholder="John_Doe@gmail.com" v-model.trim="currentStudent.email">
                                <label for="StudentEmailFloat">
                                    <font-awesome-icon :icon="['far', 'envelope']" />
                                    Student Email
                                </label>
                                <span class="error-feedback" v-if="v$.currentStudent.email.$error">
                                    {{ v$.currentStudent.email.$errors[0].$message }}
                                </span>
                            </div>
                            <div class="form-floating mb-3" :class="{ 'form-group-error': v$.currentStudent.phone.$error }">
                                <input type="tel" class="form-control" id="StudentPhoneFloat" placeholder="0123456789"
                                    v-model.trim="currentStudent.phone">
                                <label for="StudentPhoneFloat">
                                    <font-awesome-icon :icon="['fas', 'phone']" />
                                    Student Phone No.
                                </label>
                                <span class="error-feedback" v-if="v$.currentStudent.phone.$error">
                                    {{ v$.currentStudent.phone.$errors[0].$message }}
                                </span>
                            </div>
                            <hr class="bg-info">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-warning" @click="updateStudent()">Update Current
                                    Student</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ************************************************  -->

        <!-- Show Delete Student Modal -->

        <div id="deleteModal" class="modal fade" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" v-if="showDeleteModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="deleteModalLabel">Delete Current Student</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="#" method="post" @click.prevent>
                            <h6 class="text-danger">Are you sure you want to delete this student?</h6>
                            <p>
                                <span> <font-awesome-icon :icon="['fas', 'user']" /> {{ currentStudent.name }} </span>
                                <br>
                                <span> <font-awesome-icon :icon="['fas', 'envelope']" /> {{ currentStudent.email }}</span>
                                <br>
                                <span> <font-awesome-icon :icon="['fas', 'phone']" /> {{ currentStudent.phone }} </span>
                            </p>
                            <hr class="bg-danger">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger" @click="deleteStudent()">
                                    Delete Current Student
                                </button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal">
                                    No, Don't Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ************************************************  -->

        <!-- Show Delete All Students Modal -->

        <div id="deleteAllModal" class="modal fade" tabindex="-1" aria-labelledby="deleteAllModalLabel" aria-hidden="true" v-if="showDeleteAllModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title text-danger" id="deleteAllModalLabel">Delete All Students</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="#" method="post" @click.prevent>
                            <h6 class="text-danger">Are you sure you want to delete All students?</h6>
                            <p class="text-danger"> Deleting all students, will remove them from your database </p>
                            <hr class="bg-danger">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-danger" @click="deleteAllStudents()">
                                    Delete Current Student
                                </button>
                                <button class="btn btn-secondary" data-bs-dismiss="modal">
                                    No, Don't Delete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ************************************************  -->

</div>
</template>

<!-- ************************************************  -->

<script>

import axios from "axios";

import useValidate from "@vuelidate/core";
import { required, email, minLength, maxLength } from "@vuelidate/validators";

// ************************************************

export default {

    name: "RegisterUsers",

    // ************************************************

    data() {
        return {
            errorMsg: "",
            successMsg: "",
            isDBConnected: "",
            isDBConnectedMsg: "",
            studentsData: [],
            v$: useValidate(),
            newStudent: {
                name: "",
                email: "",
                phone: "",
            },
            currentStudent: {},
            showDeleteAllModal: true,
            showDeleteModal: true,
            showUpdateModal: true,
            showAddModal: true,
        }
    },

    // ************************************************

    validations() {
        return {
            newStudent: {
                name: { required, minLength: minLength(5) },
                email: { required, email },
                phone: { required, minLength: minLength(10), maxLength:maxLength(10) },
            },
            currentStudent: {
                name: { required, minLength: minLength(5) },
                email: { required, email },
                phone: { required, minLength: minLength(10), maxLength: maxLength(10) },
            },
        }
    },

    // ************************************************

    mounted() {
        this.dbConnection();
        this.getStudentData();
    },

    // ************************************************

    methods: {

        formatPhoneNumber(num) {
            var cleaned = ('' + num).replace(/\D/g, '');
            var match = cleaned.match(/^(\d{3})(\d{3})(\d{4})$/);
            if (match) {
                return '(' + match[1] + ') ' + match[2] + '-' + match[3];
            } else {
                return null;
            }
        },

        async getStudentData() {
            let res = await axios.get(`http://localhost/student_dashboard/src/api/students.php?action=read`);
            const resData = res.data;
            if (res.status == 200) {
                if (resData.error) {
                    // if error
                    this.errorMsg = resData.message;
                } else {
                    // if everything is ok
                    this.studentsData = resData.students;
                }
            }
        },

        async dbConnection() {
            let res = await axios.get(`http://localhost/student_dashboard/src/api/students.php`);
            const resData = res.data;
            this.isDBConnected = resData.is_db_connected;
            this.isDBConnectedMsg = resData.connection_msg;
        },

        addModalDialog(val) {
            this.showAddModal = val;
        },

        async addNewStudent() {
            // console.log("Hello");
            this.v$.newStudent.$validate();
            if (!this.v$.newStudent.$error) {

                this.addModalDialog(false);
                this.clearOldMsgs();

                let formData = this.toFormData(this.newStudent);
                let res = await axios.post(`http://localhost/student_dashboard/src/api/students.php?action=create`, formData);
                const resData = res.data;
                if (res.status == 200) {
                    if (resData.error) {
                        // if error
                        this.errorMsg = resData.message;
                    } else {
                        // if everything is ok
                        this.successMsg = resData.message;
                        this.newStudent = {name:"", email:"", phone:""};
                        this.getStudentData();
                        this.v$.newStudent.$reset();
                    }
                }

            }
        },

        updateModalDialog(val) {
            this.showUpdateModal = val;
        },

        async updateStudent() {
            this.v$.currentStudent.$validate();
            if (!this.v$.currentStudent.$error) {
                this.updateModalDialog(false);
                this.clearOldMsgs();
                let formData = this.toFormData(this.currentStudent);
                let res = await axios.post(`http://localhost/student_dashboard/src/api/students.php?action=update`, formData);
                const resData = res.data;
                if (res.status == 200) {
                    if (resData.error) {
                        // if error
                        this.errorMsg = resData.message;
                    } else {
                        // if everything is ok
                        this.successMsg = resData.message;
                        this.currentStudent = {};
                        this.getStudentData();
                        this.v$.currentStudent.$reset();
                    }
                }
            }
        },

        selectStudent(std) {
            this.currentStudent = std;
        },

        deleteAllModalDialog(val) {
            this.showDeleteAllModal = val;
        },

        clearOldMsgs() {
            this.successMsg = "";
            this.errorMsg = "";
        },

        async deleteAllStudents() {
            this.deleteAllModalDialog(false);
            this.clearOldMsgs();
            let res = await axios.get(`http://localhost/student_dashboard/src/api/students.php?action=deleteAll`);
            const resData = res.data;
            if (res.status == 200) {
                if (resData.error) {
                    // if error
                    this.errorMsg = resData.message;
                } else {
                    // if everything is ok
                    this.successMsg = resData.message;
                    this.studentsData = "";
                }
            }
        },

        deleteModalDialog(val) {
            this.showDeleteModal = val;
        },

        toFormData(obj) {
            var fd = new FormData();
            for (var i in obj) {
                console.log(i + "=>" + obj[i]);
                fd.append(i, obj[i]);
            }
            return fd;
        },

        async deleteStudent() {
            let formData = this.toFormData(this.currentStudent);
            this.deleteModalDialog(false);
            this.clearOldMsgs();
            let res = await axios.post(`http://localhost/student_dashboard/src/api/students.php?action=delete`, formData);
            const resData = res.data;
            if (res.status == 200) {
                if (resData.error) {
                    // if error
                    this.errorMsg = resData.message;
                } else {
                    // if everything is ok
                    let newStudentsData = this.studentsData.filter((ele) => ele.id != this.currentStudent.id);
                    console.table(newStudentsData);
                    this.successMsg = resData.message;
                    this.currentStudent = {};
                    // this.getStudentData();
                    this.studentsData = newStudentsData;
                }
            }
        },

    },

}

// ************************************************

</script>

<!-- ************************************************ -->

<style scoped lang="scss">
h1 {
    background-color: cadetblue;
    color: white;
    padding: 8px;
    margin-top: 0;
    margin-bottom: 50px;
}

.form-group-error {
    color: red;
}

.form-group-error input {
    border-color: red;
}
</style>
