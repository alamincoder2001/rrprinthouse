<style>
	.v-select{
		margin-bottom: 5px;
	}
	.v-select.open .dropdown-toggle{
		border-bottom: 1px solid #ccc;
	}
	.v-select .dropdown-toggle{
		padding: 0px;
		height: 25px;
	}
	.v-select input[type=search], .v-select input[type=search]:focus{
		margin: 0px;
	}
	.v-select .vs__selected-options{
		overflow: hidden;
		flex-wrap:nowrap;
	}
	.v-select .selected-tag{
		margin: 2px 0px;
		white-space: nowrap;
		position:absolute;
		left: 0px;
	}
	.v-select .vs__actions{
		margin-top:-5px;
	}
	.v-select .dropdown-menu{
		width: auto;
		overflow-y:auto;
	}
	#salaryAdvance label{
		font-size:13px;
	}
	#salaryAdvance select{
		border-radius: 3px;
		padding: 0;
	}
	#salaryAdvance .add-button{
		padding: 2.5px;
		width: 28px;
		background-color: #298db4;
		display:block;
		text-align: center;
		color: white;
	}
	#salaryAdvance .add-button:hover{
		background-color: #41add6;
		color: white;
	}
</style>

<div id="salaryAdvance">
    <div class="row" style="border-bottom: 1px solid #ccc;padding-bottom: 15px;margin-bottom: 15px;">
		<div class="col-md-12">
			<form @submit.prevent="saveAdvancePayment">
				<div class="row">
					<div class="col-md-5 col-md-offset-1">
                        <div class="form-group">
							<label class="col-md-4 control-label">Payment Date</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<input type="date" class="form-control" v-model="payment.date" required @change="getAdvanceSalary" v-bind:disabled="userType == 'u' ? true : false">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Payment Type</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<select class="form-control" v-model="payment.type" required>
									<option value="cash">Cash</option>
									<option value="bank">Bank</option>
								</select>
							</div>
						</div>
						<div class="form-group" style="display:none;" v-bind:style="{display: payment.type == 'bank' ? '' : 'none'}">
							<label class="col-md-4 control-label">Bank Account</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<v-select v-bind:options="filteredAccounts" v-model="selectedAccount" label="display_text" placeholder="Select account"></v-select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Employee</label>
							<label class="col-md-1">:</label>
							<div class="col-md-6 col-xs-11">
								<select class="form-control" v-if="employees.length == 0"></select>
								<v-select v-bind:options="employees" v-model="selectedEmployee" label="display_name" v-if="employees.length > 0"></v-select>
							</div>
							<div class="col-md-1 col-xs-1" style="padding-left:0;margin-left: -3px;">
								<a href="/employee" target="_blank" class="add-button"><i class="fa fa-plus"></i></a>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-md-4 control-label">Salary Range</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<input type="text" class="form-control" v-model="selectedEmployee.salary_range" disabled>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Select Month</label>
							<label class="col-md-1">:</label>
							<div class="col-md-6 col-xs-11">
								<select class="form-control" v-if="months.length == 0"></select>
								<v-select v-bind:options="months" v-model="selectedMonth" label="month_name" v-if="months.length > 0"></v-select>
							</div>
							<div class="col-md-1 col-xs-1" style="padding-left:0;margin-left: -3px;">
								<a href="/month" target="_blank" class="add-button"><i class="fa fa-plus"></i></a>
							</div>
						</div>
						
					</div>

					<div class="col-md-5">
						<div class="form-group">
							<label class="col-md-4 control-label">Description</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
                                <textarea class="form-control" rows="2"  v-model="payment.note"></textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label">Amount</label>
							<label class="col-md-1">:</label>
							<div class="col-md-7">
								<input type="number" class="form-control" v-model="payment.amount" required>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-7 col-md-offset-5">
								<input type="submit" class="btn btn-success btn-sm" v-bind:disabled="progress ? true : false" value="Save">
								<input type="button" class="btn btn-danger btn-sm" value="Cancel" @click="resetForm">
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

    <div class="row">
		<div class="col-sm-12 form-inline">
			<div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div>
		</div>
		<div class="col-md-12">
			<div class="table-responsive">
				<datatable :columns="columns" :data="payments" :filter-by="filter" style="margin-bottom: 5px;">
					<template scope="{ row }">
						<tr>
							<td>{{ row.code }}</td>
							<td>{{ row.date }}</td>
							<td>{{ row.Employee_Name }}</td>
							<td>{{ row.month_name }}</td>
							<td>{{ row.payment_type }}</td>
							<td>{{ row.amount }}</td>
							<td>{{ row.note }}</td>
							<td>{{ row.add_by }}</td>
							<td>
								<button type="button" class="button edit" @click="window.location = `/advanceSalaryReport/${row.id}`">
									<i class="fa fa-file-o"></i>
								</button>

								<?php if($this->session->userdata('accountType') != 'u'){?>
								<button type="button" class="button edit" @click="editPayment(row)">
									<i class="fa fa-pencil"></i>
								</button>
								<button type="button" class="button" @click="deletePayment(row.id)">
									<i class="fa fa-trash"></i>
								</button>
								<?php }?>
                                
							</td>
						</tr>
					</template>
				</datatable>
				<datatable-pager v-model="page" type="abbreviated" :per-page="per_page" style="margin-bottom: 50px;"></datatable-pager>
			</div>
		</div>
	</div>
</div>

<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>
<script src="<?php echo base_url(); ?>assets/js/moment.min.js"></script>

<script>
    Vue.component('v-select', VueSelect.VueSelect);
    new Vue({
        el: "#salaryAdvance",

        data() {
            return {
                payment: {
                    id: null,
                    date: moment().format('YYYY-MM-DD'),
                    type: 'cash',
                    bank_id: null,
                    employee_id : null,
                    month_id : null,
                    note: '',
                    amount: 0.00
                },
                payments: [],

                accounts: [],
                selectedAccount: null,
                employees: [],
                selectedEmployee: {
                    Employee_SlNo: null,
                    display_name: 'select employee',
                    salary_range: 0
                },
                months: [],
                selectedMonth: {
                    month_id: null,
                    month_name: 'month name'
                },
                progress: false,
                userType: '<?php echo $this->session->userdata("accountType");?>',

                columns: [
                    { label: 'Transaction Id', field: 'code', align: 'center' },
                    { label: 'Date', field: 'date', align: 'center' },
                    { label: 'Employee', field: 'Employee_Name', align: 'center' },
                    { label: 'Month', field: 'month_name', align: 'center' },
                    { label: 'Payment Type', field: 'payment_type', align: 'center' },
                    { label: 'Amount', field: 'amount', align: 'center' },
                    { label: 'Description', field: 'note', align: 'center' },
                    { label: 'Saved By', field: 'add_by', align: 'center' },
                    { label: 'Action', align: 'center', filterable: false }
                ],
                page: 1,
                per_page: 10,
                filter: ''
            }
        },

        computed: {
            filteredAccounts(){
                let accounts = this.accounts.filter(account => account.status == '1');
                return accounts.map(account => {
                    account.display_text = `${account.account_name} - ${account.account_number} (${account.bank_name})`;
                    return account;
                })
            },
        },

        created() {
            this.getAccounts();
            this.getEmployees();
            this.getMonths();
            this.getAdvanceSalary();
        },

        methods: {
            getAccounts(){
                axios.get('/get_bank_accounts')
                .then(res => {
                    this.accounts = res.data;
                })
            },
            
            getEmployees() {
				axios.get('/get_employees').then(res => {
                    this.employees = res.data;
				})
			},

            getMonths() {
                axios.get('/get_months').then(res => {
                    this.months = res.data;
                })
            },

            getAdvanceSalary() {
                let data = {
					dateFrom: this.payment.date,
					dateTo: this.payment.date
				}
				axios.post('/get_salary_advance', data).then(res => {
					this.payments = res.data;
				})
            },

            saveAdvancePayment() {
                if(this.payment.type == 'bank'){
					if(this.selectedAccount == null){
						alert('Select an account');
						return;
					} else {
						this.payment.bank_id = this.selectedAccount.account_id;
					}
				} else {
					this.payment.bank_id = null;
				}

				if(this.selectedEmployee == null || this.selectedEmployee.Employee_SlNo == undefined){
					alert('Select Employee');
					return;
				}

				if(this.selectedMonth == null || this.selectedMonth.month_id == undefined){
					alert('Select Month');
					return;
				}

                this.progress = true;

                let url = '/add_salary_advance';
                if(this.payment.id != null) {
                    url = '/update_salary_payment';
                }
                this.payment.employee_id = this.selectedEmployee.Employee_SlNo;
                this.payment.month_id = this.selectedMonth.month_id;

                axios.post(url, this.payment).then(res => {
                    let r = res.data;
					alert(r.message);
					if(r.success){
						this.resetForm();
						this.getAdvanceSalary();
                        this.progress = false;
						let invoiceConfirm = confirm('Do you want to view invoice?');
						if(invoiceConfirm == true){
							window.open('/advanceSalaryReport/'+r.paymentId, '_blank');
						}
					}
                })
            },

            editPayment(payment) {
                let keys = Object.keys(this.payment);
				keys.forEach(key => {
					this.payment[key] = payment[key];
				})

				this.selectedEmployee = {
                    Employee_SlNo: payment.employee_id,
                    display_name: payment.Employee_Name,
                    salary_range: payment.salary_range
				}

                this.selectedMonth = {
                    month_id: payment.month_id,
                    month_name: payment.month_name
                }

				if(payment.type == 'bank'){
					this.selectedAccount = {
						account_id: payment.account_id,
						account_name: payment.account_name,
						account_number: payment.account_number,
						bank_name: payment.bank_name,
						display_text: `${payment.account_name} - ${payment.account_number} (${payment.bank_name})`
					}
				}
            },

            deletePayment(paymentId) {
                let deleteConfirm = confirm('Are you sure?');
				if(deleteConfirm == false){
					return;
				}
				axios.post('/delete_advance_salary', {paymentId: paymentId}).then(res => {
					let r = res.data;
					alert(r.message);
					if(r.success){
						this.getAdvanceSalary();
					}
				})
            },

            resetForm() {
                this.payment = {
					id: null,
                    date: moment().format('YYYY-MM-DD'),
                    type: 'cash',
                    bank_id: null,
                    employee_id : null,
                    month_id : null,
                    note: '',
                    amount: 0.00
                }

                this.selectedEmployee = {
                    Employee_SlNo: null,
                    display_name: 'select employee',
                    salary_range: 0
                }

                this.selectedMonth = {
                    month_id: null,
                    month_name: 'month name'
                }

                this.selectedAccount = null;
            }
        }

    })
</script>