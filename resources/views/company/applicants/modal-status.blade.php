<!-- Update Candidacy Status Modal Component -->
<div x-show="statusModalOpen"
     x-cloak
     x-effect="document.body.classList.toggle('overflow-hidden', statusModalOpen)"
     @keydown.escape.window="statusModalOpen = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     role="dialog" 
     aria-modal="true" 
     aria-labelledby="modal-status-title">

    <!-- Modal Backdrop with Blur -->
    <div x-show="statusModalOpen"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="statusModalOpen = false"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

    <!-- Centered Modal Container -->
    <div class="flex min-h-full items-center justify-center p-3 sm:p-6 lg:p-8">
        <div x-show="statusModalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop
             class="relative w-full max-w-xl bg-white rounded-3xl border border-slate-200/90 shadow-2xl overflow-hidden my-6">

            <!-- Modal Header -->
            <div class="px-6 py-5 sm:px-8 sm:py-6 border-b border-slate-100 bg-gradient-to-r from-emerald-50/60 via-slate-50/40 to-white flex items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-2xl bg-emerald-600 text-white flex items-center justify-center shadow-md shadow-emerald-600/20 shrink-0">
                        <i class="fa-solid fa-sliders text-lg"></i>
                    </div>
                    <div>
                        <h3 id="modal-status-title" class="text-lg sm:text-xl font-black text-slate-900 tracking-tight">
                            Update Candidacy Status
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            <span x-text="statusApp.candidateName" class="font-bold text-slate-800"></span> • <span x-text="statusApp.roleTitle" class="text-emerald-700 font-medium"></span>
                        </p>
                    </div>
                </div>
                <button type="button" 
                        @click="statusModalOpen = false"
                        class="w-9 h-9 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors cursor-pointer"
                        title="Close (Esc)">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Modal Form Body -->
            <form :action="statusApp.updateUrl" method="POST" id="update-status-modal-form" class="px-6 py-6 sm:px-8 space-y-6">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" :value="statusApp.status">

                <!-- Stage Selection Cards -->
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-2.5">
                        Candidacy Stage <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5">
                        <!-- Pending Review -->
                        <button type="button" 
                                @click="statusApp.status = 'pending'"
                                :class="statusApp.status === 'pending' ? 'border-amber-500 bg-amber-50/80 text-amber-950 ring-2 ring-amber-500/20 shadow-xs' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                                class="p-3 rounded-2xl border-2 text-left transition-all cursor-pointer flex flex-col justify-between gap-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-base">⏳</span>
                                <span class="w-2 h-2 rounded-full" :class="statusApp.status === 'pending' ? 'bg-amber-500' : 'bg-transparent'"></span>
                            </div>
                            <div>
                                <span class="text-xs font-bold block">Pending</span>
                                <span class="text-[10px] text-slate-500">Initial submission</span>
                            </div>
                        </button>

                        <!-- Under Review -->
                        <button type="button" 
                                @click="statusApp.status = 'under_review'"
                                :class="statusApp.status === 'under_review' ? 'border-sky-500 bg-sky-50/80 text-sky-950 ring-2 ring-sky-500/20 shadow-xs' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                                class="p-3 rounded-2xl border-2 text-left transition-all cursor-pointer flex flex-col justify-between gap-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-base">🔍</span>
                                <span class="w-2 h-2 rounded-full" :class="statusApp.status === 'under_review' ? 'bg-sky-500' : 'bg-transparent'"></span>
                            </div>
                            <div>
                                <span class="text-xs font-bold block">Under Review</span>
                                <span class="text-[10px] text-slate-500">Assessing profile</span>
                            </div>
                        </button>

                        <!-- Shortlisted -->
                        <button type="button" 
                                @click="statusApp.status = 'shortlisted'"
                                :class="statusApp.status === 'shortlisted' ? 'border-indigo-500 bg-indigo-50/80 text-indigo-950 ring-2 ring-indigo-500/20 shadow-xs' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                                class="p-3 rounded-2xl border-2 text-left transition-all cursor-pointer flex flex-col justify-between gap-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-base">⭐</span>
                                <span class="w-2 h-2 rounded-full" :class="statusApp.status === 'shortlisted' ? 'bg-indigo-500' : 'bg-transparent'"></span>
                            </div>
                            <div>
                                <span class="text-xs font-bold block">Shortlisted</span>
                                <span class="text-[10px] text-slate-500">Promising fit</span>
                            </div>
                        </button>

                        <!-- Interviewed -->
                        <button type="button" 
                                @click="statusApp.status = 'interviewed'"
                                :class="statusApp.status === 'interviewed' ? 'border-purple-500 bg-purple-50/80 text-purple-950 ring-2 ring-purple-500/20 shadow-xs' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                                class="p-3 rounded-2xl border-2 text-left transition-all cursor-pointer flex flex-col justify-between gap-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-base">💬</span>
                                <span class="w-2 h-2 rounded-full" :class="statusApp.status === 'interviewed' ? 'bg-purple-500' : 'bg-transparent'"></span>
                            </div>
                            <div>
                                <span class="text-xs font-bold block">Interviewed</span>
                                <span class="text-[10px] text-slate-500">Screening complete</span>
                            </div>
                        </button>


                        <!-- Rejected -->
                        <button type="button" 
                                @click="statusApp.status = 'rejected'"
                                :class="statusApp.status === 'rejected' ? 'border-rose-500 bg-rose-50/80 text-rose-950 ring-2 ring-rose-500/20 shadow-xs' : 'border-slate-200 text-slate-600 hover:border-slate-300 hover:bg-slate-50'"
                                class="p-3 rounded-2xl border-2 text-left transition-all cursor-pointer flex flex-col justify-between gap-1.5">
                            <div class="flex items-center justify-between">
                                <span class="text-base">❌</span>
                                <span class="w-2 h-2 rounded-full" :class="statusApp.status === 'rejected' ? 'bg-rose-500' : 'bg-transparent'"></span>
                            </div>
                            <div>
                                <span class="text-xs font-bold block">Rejected</span>
                                <span class="text-[10px] text-slate-500">Not selected</span>
                            </div>
                        </button>
                    </div>
                </div>

                <!-- Recruiter Feedback Note -->
                <div>
                    <label for="modal-company-notes" class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Recruiter Feedback / Notes <span class="text-slate-400 font-normal normal-case">(optional)</span>
                    </label>
                    <textarea id="modal-company-notes" 
                              name="company_notes" 
                              x-model="statusApp.companyNotes"
                              rows="3" 
                              placeholder="e.g. Scheduled technical interview for next Tuesday at 2:00 PM via Google Meet..." 
                              class="w-full px-4 py-3 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-slate-900 transition-all placeholder-slate-400 resize-none"></textarea>
                    <p class="text-[11px] text-slate-400 mt-1">This note is saved with the candidacy record and shared in the pipeline overview.</p>
                </div>

                <!-- Modal Actions -->
                <div class="pt-5 border-t border-slate-100 flex items-center justify-end gap-3">
                    <button type="button" 
                            @click="statusModalOpen = false"
                            class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-colors cursor-pointer">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-emerald-600/25 transition-all flex items-center gap-2 cursor-pointer">
                        <i class="fa-solid fa-check text-xs"></i>
                        <span>Save Stage</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
