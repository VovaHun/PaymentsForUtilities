package dev.akat.smartmeter.ui.accountList

import android.os.Bundle
import android.view.*
import androidx.fragment.app.Fragment
import androidx.fragment.app.viewModels
import androidx.lifecycle.Observer
import androidx.lifecycle.ViewModelProvider
import androidx.navigation.fragment.findNavController
import androidx.recyclerview.widget.DividerItemDecoration
import dev.akat.smartmeter.MeterApplication
import dev.akat.smartmeter.R
import dev.akat.smartmeter.di.AppComponent
import kotlinx.android.synthetic.main.fragment_account_list.*
import javax.inject.Inject

class AccountListFragment : Fragment(), AccountListAdapter.EventListener {

    @Inject
    lateinit var viewModelFactory: ViewModelProvider.Factory

    private val appComponent: AppComponent by lazy(mode = LazyThreadSafetyMode.NONE) {
        (activity?.application as MeterApplication).appComponent
    }

    private val viewModel: AccountListViewModel by viewModels { viewModelFactory }

    private lateinit var accountListAdapter: AccountListAdapter

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        appComponent.inject(this)
    }

    override fun onCreateView(
        inflater: LayoutInflater,
        container: ViewGroup?,
        savedInstanceState: Bundle?
    ): View? =
        inflater.inflate(R.layout.fragment_account_list, container, false)

    override fun onViewCreated(view: View, savedInstanceState: Bundle?) {
        super.onViewCreated(view, savedInstanceState)
        setHasOptionsMenu(true)

        initRecyclerView()
        showDataView()
        subscribeLoading()

        account_list_swipe.setOnRefreshListener {
            viewModel.loadAccounts()
            account_list_swipe.isRefreshing = false
        }

        account_list_fab.setOnClickListener {
            findNavController().navigate(R.id.action_accountListFragment_to_queryListFragment)
        }
    }

    override fun onItemClick(id: Long) {
        val action =
            AccountListFragmentDirections.actionAccountListFragmentToAccountInfoFragment(id)
        findNavController().navigate(action)
    }

    override fun onCreateOptionsMenu(menu: Menu, inflater: MenuInflater) {
        super.onCreateOptionsMenu(menu, inflater)
        inflater.inflate(R.menu.menu_acc_list, menu)
    }

    override fun onOptionsItemSelected(item: MenuItem): Boolean {
        when (item.itemId) {
            R.id.menu_acc_list_log_out -> {
                viewModel.logOut()
                findNavController().navigate(R.id.action_accountListFragment_to_login)
            }
        }
        return super.onOptionsItemSelected(item)
    }

    private fun initRecyclerView() {
        accountListAdapter = AccountListAdapter(this)
        account_list_rv.adapter = accountListAdapter
        account_list_rv.addItemDecoration(
            DividerItemDecoration(
                context,
                DividerItemDecoration.VERTICAL
            )
        )

        viewModel.accounts.observe(viewLifecycleOwner, Observer { accounts ->
            accountListAdapter.submitList(accounts)
            showDataView()
        })
    }

    private fun subscribeLoading() {
        viewModel.isLoading.observe(viewLifecycleOwner, Observer {
            it.getContentIfNotHandled()?.let { isLoading ->
                if (isLoading) showNoItemLoading()
                else showDataView()
            }
        })
    }

    private fun showDataView() {
        if (accountListAdapter.itemCount > 0) {
            account_list_rv.visibility = View.VISIBLE
            accounts_no_item.visibility = View.GONE
            accounts_no_item_loading.visibility = View.GONE
        } else {
            account_list_rv.visibility = View.INVISIBLE
            accounts_no_item.visibility = View.VISIBLE
            accounts_no_item_loading.visibility = View.GONE
        }
    }

    private fun showNoItemLoading() {
        account_list_rv.visibility = View.INVISIBLE
        accounts_no_item.visibility = View.GONE
        accounts_no_item_loading.visibility = View.VISIBLE
    }
}