<div id="bothcolors">
	<form @submit.prevent="saveBothcolor">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom: 15px;">
			<div class="col-xs-12 col-md-6 col-md-offset-3">
				<div class="form-group clearfix">
					<label class="control-label col-xs-4">Both Color Name:</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" v-model="bothcolor.color_name">
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-xs-4">Amount:</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" v-model="bothcolor.amount">
					</div>
				</div>
				<div class="form-group clearfix">
					<label for="" class="col-xs-4"></label>
					<div class="text-center col-xs-8">
						<button :disabled="onProgress ? true: false" type="submit" class="btn btn-success btn-xs" style="padding:5px 15px;">Save</button>
					</div>
				</div>
			</div>
		</div>
	</form>

	<div class="row">
		<div class="col-xs-12 col-sm-12 form-inline">
			<div class="form-group">
				<label for="filter" class="sr-only">Filter</label>
				<input type="text" class="form-control" v-model="filter" placeholder="Filter">
			</div>
		</div>
		<div class="col-xs-12 col-md-12">
			<div class="table-responsive">
				<datatable :columns="columns" :data="bothcolors" :filter-by="filter">
					<template scope="{ row }">
						<tr>
							<td>{{ row.color_SiNo }}</td>
							<td>{{ row.color_name }}</td>
							<td>{{ row.amount }}</td>
							<td>

								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<button type="button" class="button edit" @click="editBothcolor(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<?php if ($this->session->userdata('accountType') != 'e') { ?>
										<button type="button" class="button" @click="deleteBothcolor(row.Key_SlNo)">
											<i class="fa fa-trash"></i>
										</button>
									<?php } ?>
								<?php } ?>

							</td>
						</tr>
					</template>
				</datatable>
				<datatable-pager v-model="page" type="abbreviated" :per-page="per_page"></datatable-pager>
			</div>
		</div>
	</div>

	<script src="<?php echo base_url(); ?>assets/js/vue/vue.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/vue/axios.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/vue/vuejs-datatable.js"></script>
	<script src="<?php echo base_url(); ?>assets/js/vue/vue-select.min.js"></script>

	<script>
		Vue.component('v-select', VueSelect.VueSelect);
		new Vue({
			el: '#bothcolors',
			data() {
				return {
					bothcolor: {
						color_SiNo: '',
						color_name: '',
						amount: '',
					},
					bothcolors: [],

					columns: [{
							label: 'Sl',
							field: 'Sl',
							align: 'center',
							filterable: false
						},
						{
							label: 'Both Color Name',
							field: 'color_name',
							align: 'center'
						},
						{
							label: 'Amount',
							field: 'amount',
							align: 'center'
						},
						{
							label: 'Action',
							align: 'center',
							filterable: false
						}
					],
					page: 1,
					per_page: 10,
					filter: '',

					onProgress: false,
				}
			},
			created() {
				this.getBothColor();
			},
			methods: {
				getBothColor() {
					axios.get('/get_bothcolor').then(res => {
						this.bothcolors = res.data;
					})
				},
				saveBothcolor() {
					if (this.bothcolor.color_name == '') {
						alert('Both side color name empty');
						return;
					}
					if (this.bothcolor.amount == '') {
						alert('Amount is empty');
						return;
					}

					let url = '/insertbothcolor';
					let data = {
						bothcolor: this.bothcolor,
					}

					this.onProgress = true;

					axios.post(url, data)
						.then(res => {
							let r = res.data;
							alert(r);
							this.getBothColor();
							this.clearForm();
							this.onProgress = false;
						})

				},
				editBothcolor(color) {
					this.bothcolor = {
						color_SiNo: color.color_SiNo,
						color_name: color.color_name,
						amount: color.amount,
					}
				},
				deleteBothcolor(colorId) {
					let deleteConfirm = confirm('Are you sure?');
					if (deleteConfirm == false) {
						return;
					}
					axios.post('/bothcolordelete', {
						colorId: colorId
					}).then(res => {
						let r = res.data;
						alert(r);
						this.getBothColor();
					})
				},
				clearForm() {
					this.bothcolor = {
						color_SiNo: '',
						color_name: '',
						amount: '',
					}
				}
			}
		})
	</script>