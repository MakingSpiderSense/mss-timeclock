<!-- Template -->
<!-- Description: Set ".modal" display to "flex" when you want it to show up. You can add buttons to the footer using javascript. For "Save", "Okay", "Submit", or "Continue", use class ".modal-continue". For "Cancel" or "Close", use class ".modal-close". Add content to the body by updating currentModal from the parent. -->
<template>
    <div class="modal" @mousedown="closeModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title"></h2>
                <button class="modal-close" @click="closeModal">
                    <svg class="modal-close-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.414 12l5.293 5.293-1.414 1.414-5.293-5.293-5.293 5.293-1.414-1.414L10.586 12 5.293 6.707l1.414-1.414L12 10.586l5.293-5.293 1.414 1.414L13.414 12z"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <component :is="currentModal"></component>
            </div>
            <div class="modal-footer"></div>
        </div>
    </div>
</template>

<!-- Scripts -->
<script>
import Default from '@/Modals/Default.vue';
import InvitesCreate from '@/Modals/InvitesCreate.vue';
import InvitesManage from '@/Modals/InvitesManage.vue';
import ManualTimeSet from '@/Modals/ManualTimeSet.vue';
import OrgCreate from '@/Modals/OrgCreate.vue';
import RatesUpdate from '@/Modals/RatesUpdate.vue';
export default {
    name: 'Modal',
    props: ['currentModal'],
    components: {
        Default,
        InvitesCreate,
        InvitesManage,
        ManualTimeSet,
        OrgCreate,
        RatesUpdate,
    },
    methods: {
        // Close modal if user clicks on semi-transparent background or close icon
        closeModal(e) {
            if (e.target.classList.contains('modal') || e.target.classList.contains('modal-close-icon') || e.target.parentElement.classList.contains('modal-close-icon')) {
                document.querySelector('.modal').style.display = 'none';
            }
        },
    },
}
</script>

<!-- Styling -->
<style lang="scss">
@import "../../sass/app.scss";
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 9999;
    display: none;
    justify-content: center;
    align-items: center;
    padding: 15px;
    .modal-content {
        position: relative;
        background-color: #fff;
        width: 100%;
        max-width: 600px;
        min-height: 300px;
        border-radius: 5px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: $color2;
            color: $white;
            .modal-title {
                font-size: 20px;
                font-weight: 600;
                margin-bottom: 0;
            }
            .modal-close {
                background-color: transparent;
                border: none;
                cursor: pointer;
                .modal-close-icon {
                    width: 20px;
                    height: 20px;
                    fill: $white;
                }
            }
        }
        .modal-body {
            padding: 20px;
            padding-bottom: 80px;
        }
        .modal-footer {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 10px 20px;
            border-top: 1px solid #eeeeee;
            position: absolute;
            bottom: 0;
            width: 100%;
            .modal-close {
                margin-left: 10px;
                position: relative;
            }
            .modal-continue {
                margin-left: 10px;
                background-color: #{$color2}cc;
                color: $white;
                margin-right: 10px;
                &:hover {
                    background-color: $color2;
                }
            }
        }
    }
}
</style>