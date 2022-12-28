<template>
	<view class="product">
		<web-view :src="currentUrl"></web-view>
    	<!--回到顶部-->
		<rf-back-top :scrollTop="scrollTop"></rf-back-top>
		<!--顶部下拉菜单-->
		<rf-nav-detail
			@hide="hideNavDetail"
			:popupShow="navDetailShow"
		>
		</rf-nav-detail>
	</view>
</template>
<script>
/**
 * @des 商品详情
 *
 * @author stav stavyan@qq.com
 * @date 2020-03-23 15:04
 * @copyright 2019
 */
import rfBackTop from '@/components/rf-back-top';
import rfNoData from '@/components/rf-no-data';
export default {
	components: {
		rfBackTop,
		rfNoData
	},
	data() {
		return {
			errorInfo: '',
			userInfo: {},
			scrollTop: 0,
			currentUrl: '',
			navDetailShow: false,
			appName: this.$mSettingConfig.appName
		};
	},
	// #ifndef MP
	onNavigationBarButtonTap(e) {
		const index = e.index;
		if (index === 0) {
			this.navDetailShow = true;
		}
	},
	// #endif
	onPageScroll(e) {
		this.scrollTop = e.scrollTop;
	},
	async onLoad(options) {
		// this.userInfo = uni.getStorageSync('userInfo') || {};
		this.currentUrl = options.url;
	},
	methods: {
		// 隐藏顶部导航
		hideNavDetail() {
			this.navDetailShow = false;
		},
	}
};
</script>
<style scoped lang="scss">
page {
	background: $page-color-base;
}
</style>
