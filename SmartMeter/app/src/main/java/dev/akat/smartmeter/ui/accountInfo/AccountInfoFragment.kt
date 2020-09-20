package dev.akat.smartmeter.ui.accountInfo

import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.Toast
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProvider
import androidx.navigation.fragment.navArgs
import androidx.recyclerview.widget.DividerItemDecoration
import dev.akat.smartmeter.MeterApplication
import dev.akat.smartmeter.R
import dev.akat.smartmeter.di.AppComponent
import dev.akat.smartmeter.model.AccountInfo
import dev.akat.smartmeter.model.ServiceInfo
import dev.akat.smartmeter.ui.MainActivity
import kotlinx.android.synthetic.main.fragment_account_info.*
import kotlinx.android.synthetic.main.include_account_info.*
import kotlinx.android.synthetic.main.include_no_item_retry.*
import javax.inject.Inject

class AccountInfoFragment : Fragment(), ServiceListAdapter.EventListener {

    @Inject
    lateinit var viewModelFactory: ViewModelProvider.Factory

    private val appComponent: AppComponent by lazy(mode = LazyThreadSafetyMode.NONE) {
        (activity?.application as MeterApplication).appComponent
    }

    private val viewModel: AccountInfoViewModel by viewModels({ activity as MainActivity }) { viewModelFactory }

    private val args: AccountInfoFragmentArgs by navArgs()
    private var isDataExists = false
    private var hasAccess = false

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        appComponent.inject(this)
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? =
        inflater.inflate(R.layout.fragment_account_info, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        showDataView()

        val accountListAdapter = ServiceListAdapter(this)
        acc_service_list_rv.adapter = accountListAdapter
        acc_service_list_rv.addItemDecoration(
            DividerItemDecoration(
                context,
                DividerItemDecoration.VERTICAL
            )
        )

        viewModel.setAccountId(args.accountId)
        viewModel.loadAccountInfo()
        viewModel.accountInfo.observe(viewLifecycleOwner, Observer { accountInfo ->
            isDataExists = true
            hasAccess = accountInfo.access
            fillAccountInfo(accountInfo)
            accountListAdapter.submitList(accountInfo.services)
            showDataView()
        })

        viewModel.errorMessage.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { message ->
                if (message.isNotEmpty())
                    Toast.makeText(context, message, Toast.LENGTH_LONG).show()
            }
        })

        viewModel.isFetched.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { isFetched ->
                if (isFetched) Toast.makeText(
                    context,
                    resources.getString(R.string.success_sent),
                    Toast.LENGTH_LONG
                ).show()
            }
        })

        viewModel.isLoading.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { isLoading ->
                if (isLoading) showNoItemLoading()
                else showDataView()
            }
        })

        no_item_retry.setOnClickListener {
            viewModel.loadAccountInfo()
        }
    }

    private fun fillAccountInfo(accountInfo: AccountInfo) {
        acc_account_using_tv.text =
            if (accountInfo.account.isUsing) resources.getString(R.string.using)
            else resources.getString(R.string.not_using)
        acc_company_tv.text = accountInfo.company.name
        acc_account_name_tv.text = accountInfo.account.name
        acc_abonent_tv.text = accountInfo.abonent.name
        acc_debt_value_tv.text =
            resources.getString(R.string.sum_format, accountInfo.debt.toString())
        acc_sum_value_tv.text =
            resources.getString(R.string.sum_format, accountInfo.summa.toString())
        acc_object_name_tv.text = accountInfo.objectInfo.name
        acc_object_kadastr_tv.text = accountInfo.objectInfo.kadastr
        acc_object_address_tv.text = accountInfo.objectInfo.address
    }

    override fun onDeviceClick(serviceInfo: ServiceInfo) {
        val dialogFragment = DeviceListFragment.newInstance(hasAccess, serviceInfo)
        dialogFragment.show(requireActivity().supportFragmentManager, dialogFragment.tag)
    }

    private fun showDataView() {
        if (isDataExists) {
            account_info_data.visibility = View.VISIBLE
            account_info_no_item.visibility = View.GONE
            account_info_no_item_loading.visibility = View.GONE
        } else {
            account_info_data.visibility = View.GONE
            account_info_no_item.visibility = View.VISIBLE
            account_info_no_item_loading.visibility = View.GONE
        }
    }

    private fun showNoItemLoading() {
        account_info_data.visibility = View.GONE
        account_info_no_item.visibility = View.GONE
        account_info_no_item_loading.visibility = View.VISIBLE
    }
}