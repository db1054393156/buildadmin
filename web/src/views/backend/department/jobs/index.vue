<template>
    <div class="default-main ba-table-box">
        <el-alert class="ba-table-alert" v-if="baTable.table.remark" :title="baTable.table.remark" type="info" show-icon />

        <!-- 表格顶部菜单 -->
        <TableHeader
            :buttons="['refresh', 'add', 'edit', 'delete', 'comSearch', 'quickSearch', 'columnDisplay']"
            :quick-search-placeholder="t('Quick search placeholder', { fields: t('department.jobs.quick Search Fields') })"
        />

        <!-- 表格 -->
        <!-- 要使用`el-table`组件原有的属性，直接加在Table标签上即可 -->
        <Table ref="tableRef" />

        <!-- 表单 -->
        <PopupForm />
    </div>
</template>

<script setup lang="ts">
import { ref, provide, onMounted } from 'vue'
import baTableClass from '/@/utils/baTable'
import { defaultOptButtons } from '/@/components/table'
import { baTableApi } from '/@/api/common'
import { useI18n } from 'vue-i18n'
import PopupForm from './popupForm.vue'
import Table from '/@/components/table/index.vue'
import TableHeader from '/@/components/table/header/index.vue'

const { t } = useI18n()
const tableRef = ref()
const optButtons = defaultOptButtons(['weigh-sort', 'edit', 'delete'])
const baTable = new baTableClass(
    new baTableApi('/admin/department.jobs/'),
    {
        pk: 'id',
        column: [
            { type: 'selection', align: 'center', operator: false, fixed: 'left' },
            { label: t('department.jobs.id'), prop: 'id', align: 'center', width: 100, sortable: 'custom', operator: 'RANGE' },
            { label: t('department.jobs.name'), prop: 'name', align: 'center', operator: 'LIKE' },
            { label: t('department.jobs.code'), prop: 'code', align: 'center', operator: 'LIKE' },
            {
                label: t('department.jobs.status'),
                prop: 'status',
                align: 'center',
                render: 'tag',
                replaceValue: { 0: t('department.jobs.status 0'), 1: t('department.jobs.status 1') },
                width: 150,
            },
            { label: t('department.jobs.weigh'), prop: 'weigh', align: 'center', sortable: 'custom', operator: false, width: 100 },
            {
                label: t('department.jobs.createtime'),
                prop: 'createtime',
                align: 'center',
                render: 'datetime',
                sortable: 'custom',
                operator: 'RANGE',
                width: 160,
            },
            {
                label: t('department.jobs.updatetime'),
                prop: 'updatetime',
                align: 'center',
                render: 'datetime',
                sortable: 'custom',
                operator: 'RANGE',
                width: 160,
            },
            { label: t('Operate'), align: 'center', width: 140, render: 'buttons', buttons: optButtons, operator: false, fixed: 'right' },
        ],
        dblClickNotEditColumn: [undefined],
        defaultOrder: { prop: 'weigh', order: 'desc' },
    },
    {
        defaultItems: { status: '1' },
    }
)

provide('baTable', baTable)

onMounted(() => {
    baTable.table.ref = tableRef.value
    baTable.mount()
    baTable.getIndex()?.then(() => {
        baTable.initSort()
        baTable.dragSort()
    })
})

defineOptions({
    name: 'department/jobs',
})
</script>

<style scoped lang="scss"></style>
