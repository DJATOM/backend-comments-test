<template>
    <div
        v-for="comment in comments"
        :key="comment.id"
        class="comment-container"
    >
        <div
            class="comment-body"
        >
            <div
                v-if="inEditing(comment.id)"
                class="comment-edit"
            >
                <textarea :id="'edit-' + comment.id">{{ comment.content }}</textarea>
                <div class="comment-actions">
                    <a @click.stop="saveComment(comment.id)">Сохранить</a>
                    <a @click.stop="editing.pop(comment.id)">Отмена</a>
                </div>
            </div>
            <template v-else>
                {{ comment.content }}
            </template>
        </div>
        <div
            v-if="!inEditing(comment.id)"
            class="comment-actions"
        >
            <a @click.stop="editComment(comment.id)">Редактировать</a>
            <a @click.stop="deleteComment(comment.id)">Удалить</a>
            <a @click.stop="addReply(comment.id)">Ответить</a>
        </div>
        <div
            v-if="inReplying(comment.id)"
            class="comment-reply"
        >
            <textarea :id="'reply-' + comment.id"></textarea>
            <div class="comment-actions">
                <a @click.stop="saveReply(comment.id)">Добавить ответ</a>
                <a @click.stop="replying.pop(comment.id)">Отмена</a>
            </div>
        </div>
        <template
            v-if="comment.replies.length > 0"
        >
            <comments-list
                :comments="comment.replies"
                @reload="$emit('reload')"
            />
        </template>
    </div>
</template>

<script>
export default {
    name    : 'CommentsList',
    props   : {
        comments: {
            type   : Array,
            default: null,
        },
    },
    emits: ["reload"],
    data () {
        return {
            editing: [],
            replying: []
        }
    },
    methods: {
        editComment (id) {
            this.editing  = []
            this.replying = []
            this.editing.push(id)
        },
        saveComment (id) {
            const data = {content: document.querySelector(`#edit-${id}`).value}
            axios.patch(`/api/comments/${id}`, data)
                .then(() => {
                    this.$swal.fire('Комментарий изменен!', '', 'success')
                    this.editing.pop(id)
                    this.$emit('reload')
                })
                .catch((error) => {
                    this.$swal(Object.values(error.response.data.errors).join('<br>'))
                })
        },
        deleteComment (id) {
            this.$swal.fire({
                title: 'Удалить комментарий?',
                showDenyButton: true,
                showCancelButton: false,
                confirmButtonText: 'Да',
                denyButtonText: `Нет`,
            }).then((result) => {
                if (result.isConfirmed) {
                    axios.delete(`/api/comments/${id}`)
                        .then(() => {
                            this.$swal.fire('Готово!', '', 'success')
                            this.$emit('reload')
                        })
                        .catch((error) => {
                            this.$swal(Object.values(error.response.data.errors).join('<br>'))
                        })
                }
            })
        },
        addReply (id) {
            this.editing  = []
            this.replying = []
            this.replying.push(id)
        },
        saveReply (id) {
            const data = {
                content:    document.querySelector(`#reply-${id}`).value,
                comment_id: id
            }
            axios.post(`/api/comments`, data)
                .then(() => {
                    this.$swal.fire('Ответ добавлен!', '', 'success')
                    this.replying.pop(id)
                    this.$emit('reload')
                })
                .catch((error) => {
                    this.$swal(Object.values(error.response.data.errors).join('<br>'))
                })
        },
        inEditing (id) {
            return this.editing.includes(id)
        },
        inReplying (id) {
            return this.replying.includes(id)
        },
    }
}
</script>