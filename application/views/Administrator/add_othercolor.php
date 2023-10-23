<div id="othercolors">
	<form @submit.prevent="saveOthercolor">
		<div class="row" style="margin-top: 10px;margin-bottom:15px;border-bottom: 1px solid #ccc;padding-bottom: 15px;">
			<div class="col-xs-12 col-md-6 col-md-offset-3">
				<div class="form-group clearfix">
					<label class="control-label col-xs-4">2nd Color Name:</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" v-model="othercolor.color_name">
					</div>
				</div>
				<div class="form-group clearfix">
					<label class="control-label col-xs-4">Amount:</label>
					<div class="col-xs-8">
						<input type="text" class="form-control" v-model="othercolor.amount">
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
				<datatable :columns="columns" :data="othercolors" :filter-by="filter">
					<template scope="{ row }">
						<tr>
							<td>{{ row.color_SiNo }}</td>
							<td>{{ row.color_name }}</td>
							<td>{{ row.amount }}</td>
							<td>

								<?php if ($this->session->userdata('accountType') != 'u') { ?>
									<button type="button" class="button edit" @click="editOthercolor(row)">
										<i class="fa fa-pencil"></i>
									</button>
									<?php if ($this->session->userdata('accountType') != 'e') { ?>
										<button type="button" class="button" @click="deleteOthercolor(row.Key_SlNo)">
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
			el: '#othercolors',
			data() {
				return {
					othercolor: {
						color_SiNo: '',
						color_name: '',
						amount: '',
					},
					othercolors: [],

					columns: [{
							label: 'Sl',
							field: 'Sl',
							align: 'center',
							filterable: false
						},
						{
							label: '2nd Color Name',
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
				this.getOtherColor();
			},
			methods: {
				getOtherColor() {
					axios.get('/get_othercolor').then(res => {
						this.othercolors = res.data;
					})
				},
				saveOthercolor() {
					if (this.othercolor.color_name == '') {
						alert('2 side color name empty');
						return;
					}

					let url = '/insertothercolor';
					let data = {
						othercolor: this.othercolor,
					}

					this.onProgress = true;

					axios.post(url, data)
						.then(res => {
							let r = res.data;
							alert(r);
							this.getOtherColor();
							this.clearForm();
							this.onProgress = false;
						})

				},
				editOthercolor(color) {
					this.othercolor = {
						color_SiNo: color.color_SiNo,
						color_name: color.color_name,
						amount: color.amount,
					}
				},
				deleteOthercolor(colorId) {
					let deleteConfirm = confirm('Are you sure?');
					if (deleteConfirm == false) {
						return;
					}
					axios.post('/othercolordelete', {
						colorId: colorId
					}).then(res => {
						let r = res.data;
						alert(r);
						this.getOtherColor();
					})
				},
				clearForm() {
					this.othercolor = {
						color_SiNo: '',
						color_name: '',
						amount: '',
					}
				}
			}
		})
	</script>