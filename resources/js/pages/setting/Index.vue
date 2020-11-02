<template>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">{{ $route.meta.title }}</h1>
                    </div>
                    <breadcrumb></breadcrumb>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                <!-- Unit Pembangkit -->
                <div class="row">
                    <router-view></router-view>
                </div>
            </div>
        </section>
    </div>

<!--    <div class="container">-->
<!--        <section class="content-header">-->
<!--            <h1>-->
<!--                Settings Page-->
<!--            </h1>-->
<!--            <breadcrumb></breadcrumb>-->
<!--        </section>-->

<!--        <section class="content">-->
<!--            <div class="row">-->
<!--                <router-view></router-view>-->
<!--            </div>-->
<!--        </section>-->
<!--    </div>-->
</template>
<script>
import Breadcrumb from '../../components/layouts/Breadcrumb.vue'
export default {
    name: 'IndexSetting',
    components: {
        'breadcrumb': Breadcrumb
    }
}
</script>
