package dev.akat.smartmeter.ui.companyList

import android.app.Activity
import android.os.Bundle
import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.view.inputmethod.InputMethodManager
import android.widget.Toast
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProvider
import androidx.navigation.fragment.findNavController
import androidx.recyclerview.widget.DividerItemDecoration
import dev.akat.smartmeter.MeterApplication
import dev.akat.smartmeter.R
import dev.akat.smartmeter.di.AppComponent
import kotlinx.android.synthetic.main.fragment_company_list.*
import javax.inject.Inject

class CompanyListFragment : Fragment(), CompanyListAdapter.EventListener {

    @Inject
    lateinit var viewModelFactory: ViewModelProvider.Factory

    private val appComponent: AppComponent by lazy(mode = LazyThreadSafetyMode.NONE) {
        (activity?.application as MeterApplication).appComponent
    }

    private val viewModel: CompanyListViewModel by viewModels { viewModelFactory }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        appComponent.inject(this)
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? =
        inflater.inflate(R.layout.fragment_company_list, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)

        initRecyclerView()
        subscribeErrors()

        viewModel.isFetched.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { isFetched ->
                if (isFetched) {
                    showToast(resources.getString(R.string.success_sent))
                    hideKeyboard()
                    findNavController().navigate(R.id.action_companyListFragment_to_queryListFragment)
                }
            }
        })
    }

    override fun onItemClick(id: Long) {
        viewModel.updateAccountQuery(company_list_name_et.text.toString(), id)
    }

    private fun initRecyclerView() {
        val companyListAdapter = CompanyListAdapter(this)
        company_list_rv.adapter = companyListAdapter
        company_list_rv.addItemDecoration(
            DividerItemDecoration(
                context,
                DividerItemDecoration.VERTICAL
            )
        )

        viewModel.companyList.observe(viewLifecycleOwner, Observer { companyList ->
            companyListAdapter.submitList(companyList)
        })
    }

    private fun subscribeErrors() {
        viewModel.errorMessage.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { message ->
                if (message.isNotEmpty()) showToast(message)
            }
        })
    }

    private fun showToast(message: String) {
        Toast.makeText(context, message, Toast.LENGTH_LONG).show()
    }

    private fun hideKeyboard() {
        val imm =
            requireContext().getSystemService(Activity.INPUT_METHOD_SERVICE) as InputMethodManager
        imm.hideSoftInputFromWindow(requireView().windowToken, 0)
    }

}