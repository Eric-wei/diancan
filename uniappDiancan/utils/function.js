export default {
	// 验证身份证号
	hideIdCardMiddle(data) {
		let reg = /^[1-9]\d{5}(18|19|20)\d{2}((0[1-9])|(10|11|12))(([0-2][1-9])|10|20|30|31)\d{3}[0-9X]$/;
		let s = reg.test(data)
		return s;
	},
	// 验证手机号码
	validatePhoneNumber(data) {
		var reg = /^1[3-9]\d{9}$/;
		return reg.test(data);
	},
	// 隐藏身份证号
	hideIdCardNumber(data) {
		// 创建正则表达式
		var reg = /(\d{6})\d{8}(\d|X)/;
		// 使用正则表达式替换身份证号中的中间数字
		var newIdCardNumber = data.replace(reg, '$1********$2');
		return newIdCardNumber;
	},
	// 隐藏手机号码
	hidePhoneNumber(phoneNumber) {
		// 使用正则表达式匹配手机号的中间四位数字
		var reg = /(\d{3})\d{4}(\d{4})/;
		// 使用replace方法将匹配到的数字替换为星号(*)
		var result = phoneNumber.replace(reg, '$1****$2');
		// 返回处理后的手机号
		return result;
	},
	// 验证银行卡号
	isValidBankCard(cardNumber) {
		const reg = /^(\d{16}|\d{19})$/;
		// 使用正则表达式测试银行卡号
		return reg.test(cardNumber);
	},
	timerFormat(num) {
		//过滤器 用于格式化时间
		let date = new Date(num); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
		let year = date.getFullYear();
		let month = ("0" + (date.getMonth() + 1)).slice(-2);
		let sdate = ("0" + date.getDate()).slice(-2);
		let hour = ("0" + date.getHours()).slice(-2);
		let minute = ("0" + date.getMinutes()).slice(-2);
		let second = ("0" + date.getSeconds()).slice(-2);
		// 拼接
		let result = year + '-' + month
		// 返回
		return result;
	},
	yearFormats(num) {
		//过滤器 用于格式化时间
		let date = new Date(num); //时间戳为10位需*1000，时间戳为13位的话不需乘1000
		let year = date.getFullYear();
		let month = ("0" + (date.getMonth() + 1)).slice(-2);
		let sdate = ("0" + date.getDate()).slice(-2);
		let hour = ("0" + date.getHours()).slice(-2);
		let minute = ("0" + date.getMinutes()).slice(-2);
		let second = ("0" + date.getSeconds()).slice(-2);
		// 拼接
		let result = year + '-' + month + '-' + sdate;
		// 返回
		return result;
	},

}
