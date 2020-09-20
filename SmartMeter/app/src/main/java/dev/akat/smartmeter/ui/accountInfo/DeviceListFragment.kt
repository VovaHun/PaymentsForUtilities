package dev.akat.smartmeter.ui.accountInfo

import android.app.Activity
import android.app.Dialog
import android.content.Context
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.view.inputmethod.InputMethodManager
import androidx.fragment.app.viewModels
import androidx.lifecycle.ViewModelProvider
import androidx.recyclerview.widget.DividerItemDecoration
import com.google.android.material.bottomsheet.BottomSheetBehavior
import com.google.android.material.bottomsheet.BottomSheetDialog
import com.google.android.material.bottomsheet.BottomSheetDialogFragment
import dev.akat.smartmeter.MeterApplication
import dev.akat.smartmeter.R
import dev.akat.smartmeter.di.AppComponent
import dev.akat.smartmeter.model.Device
import dev.akat.smartmeter.model.ServiceInfo
import dev.akat.smartmeter.ui.MainActivity
import dev.akat.smartmeter.utils.PARAM_DEVICES
import dev.akat.smartmeter.utils.PARAM_HAS_ACCESS
import kotlinx.android.synthetic.main.sheet_device_list.*
import javax.inject.Inject

class DeviceListFragment : BottomSheetDialogFragment(), DeviceListAdapter.EventListener {

    @Inject
    lateinit var viewModelFactory: ViewModelProvider.Factory

    private val appComponent: AppComponent by lazy(mode = LazyThreadSafetyMode.NONE) {
        (activity?.application as MeterApplication).appComponent
    }

    private val viewModel: AccountInfoViewModel by viewModels({ activity as MainActivity }) { viewModelFactory }

    private lateinit var dialog: BottomSheetDialog
    private lateinit var behavior: BottomSheetBehavior<View>

    override fun onCreateDialog(savedInstanceState: Bundle?): Dialog {
        dialog = super.onCreateDialog(savedInstanceState) as BottomSheetDialog
        dialog.setOnShowListener {
            val d = it as BottomSheetDialog
            val sheet = d.findViewById<View>(com.google.android.material.R.id.design_bottom_sheet)
            behavior = BottomSheetBehavior.from(sheet!!)
            behavior.isHideable = false
            behavior.state = BottomSheetBehavior.STATE_COLLAPSED
        }
        return dialog
    }

    override fun onAttach(context: Context) {
        super.onAttach(context)
        appComponent.inject(this)
    }

    override fun onCreateView(
        inflater: LayoutInflater, container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? =
        inflater.inflate(R.layout.sheet_device_list, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        initRecyclerView(
            arguments?.getParcelableArrayList(PARAM_DEVICES) ?: ArrayList(),
            arguments?.getBoolean(PARAM_HAS_ACCESS) ?: false
        )
    }

    private fun initRecyclerView(devices: List<Device>, hasAccess: Boolean) {
        val adapter = DeviceListAdapter(this, hasAccess)
        adapter.submitList(devices)
        device_list_rv.adapter = adapter

        if (devices.size > 1)
            device_list_rv.addItemDecoration(
                DividerItemDecoration(
                    context,
                    DividerItemDecoration.VERTICAL
                )
            )
    }

    override fun onSendClick(deviceId: Long, indication: Double) {
        viewModel.updateDeviceIndications(deviceId, indication)
        hideKeyboard()
        dialog.dismiss()
    }

    private fun hideKeyboard() {
        val imm =
            requireContext().getSystemService(Activity.INPUT_METHOD_SERVICE) as InputMethodManager
        imm.hideSoftInputFromWindow(requireView().windowToken, 0)
    }

    companion object {
        fun newInstance(hasAccess: Boolean, service: ServiceInfo) = DeviceListFragment().apply {
            arguments = Bundle().apply {
                putBoolean(PARAM_HAS_ACCESS, hasAccess)
                putParcelableArrayList(PARAM_DEVICES, ArrayList(service.devices))
            }
        }
    }
}